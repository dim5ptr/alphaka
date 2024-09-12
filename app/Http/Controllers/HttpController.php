<?php

namespace App\Http\Controllers;

// use Str;
use App\Models\User;
use App\Mail\RegisterMail;
use App\Mail\ActivationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str;




class HttpController extends Controller
{
    const API_URL = 'http://192.168.1.24:14041/api';
    const API_KEY = '5af97cb7eed7a5a4cff3ed91698d2ffb';
    private static $access_token = null;



    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
{
    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
        'confirmpassword' => 'required|same:password',
    ]);

    try {
        // Mengirim permintaan ke API untuk registrasi
        $response = Http::withHeaders([
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/register.json', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Ambil data dari respons API
        $data = $response->json();
        Log::info('API Response:', $data);

        // Memeriksa apakah respons sukses
        if ($response->successful() && $data['success']) {
            // Jika berhasil dan API mengirim respons yang sukses
            if (isset($data['activation_key'])) {
                // Simpan data token ke session
                session([
                    'verification_token' => $data['activation_key'],
                    'email' => $request->email,
                ]);

                // Logging untuk debug
                Log::info('Generated Token: ' . session('verification_token'));

                // Kirim email untuk verifikasi akun
                Log::info('Preparing to send verification email');
                Mail::send('emails/verification', ['token' => session('verification_token')], function($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Email Activation');
                });
                Log::info('Verification email sent');


                return redirect('/verify')->with('success_message', 'Please check your email to activate your account.');
            } else {
                // Jika tidak ada activation_key, mungkin API sudah mengirim emailnya sendiri
                return redirect('/verify')->with('success_message', $data['data']);
            }

        } else {
            // Jika gagal, tampilkan pesan error dari API
            return back()->withErrors([
                'error_message' => $data['data'],
            ])->withInput();
        }

    } catch (\Illuminate\Http\Client\RequestException $e) {
        Log::error('HTTP Request failed: ' . $e->getMessage());
        return back()->withErrors([
            'error_message' => 'HTTP Request failed: ' . $e->getMessage(),
        ])->withInput();
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return back()->withErrors([
            'error_message' => 'Something went wrong, try again! ' . $e->getMessage(),
        ])->withInput();
    }
}

public function showActivationForm(Request $request)
{
    // Ambil token dari session
    $activationKey = session('verification_token');

    // Validasi token
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
    ])->post(self::API_URL . '/sso/user_verify.json', [
        'activation_key' => $activationKey,
    ]);

    $data = $response->json();
    Log::info('Activation API Response:', $data);

    if ($response->successful() && $data['success']) {
        // Token berhasil digunakan
        return view('Activation')->with('success_message', 'Your account has been activated successfully.');
    } else {
        // Token tidak valid atau aktivasi gagal
        return view('gactivation')->with('error_message', 'Failed to activate your account.');
    }
}

// public function register(Request $request)
//     {
//         // Kirim request ke API dengan header dan body yang sesuai
//         $response = Http::withHeaders([
//             'x-api-key' => self::API_KEY,
//         ])->post(self::API_URL . '/sso/register.json', [
//             'email' => $request->email,
//             'password' => $request->password,
//             'address' => $request->address,
//         ]);

//         // Ambil respons JSON dari API
//         $result = $response->json();

//         if (isset($result['success']) && $result['success'] == true) {
//             // Kirim email verifikasi
//             Mail::to($request->email)->send(new ActivationMail($result['data']));

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Registration successful. Please check your email for verification.',
//             ], 200);
//         } else {
//             return response()->json([
//                 'success' => false,
//                 'message' => $result['data'] ?? 'Registration failed.',
//             ], 400);
//         }
//     }



    public function showuserverify()
    {
        return view('verification');
    }


    public function showLogin()
    {
        return view('login');
    }

    public function showLinkRequestForm()
    {
        return view('forgetpassword');
    }

public function sendResetLinkEmail(Request $request)
{
    $this->validateEmail($request);

    // Panggil API untuk mendapatkan reset token
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
    ])->post(self::API_URL . '/api/get_reset_token.json', [
        'email' => $request->email,
    ]);

    // Ambil data dari respons API
    $responseData = $response->json();

    if ($response->successful() && isset($responseData['token'])) {
        // Simpan data token ke session
        session([
            'reset_token' => $responseData['token'],
            'expired_date' => $responseData['expired_date'] ?? null,
            'email' => $responseData['email'] ?? $request->email,
        ]);

        // Logging untuk debug
        Log::info('Generated Token: ' . session('reset_token'));
        Log::info('Expires At: ' . session('expired_date'));

        // Kirim email untuk reset password
        Mail::send('emails/forgotpassword', ['token' => session('reset_token')], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });

        return back()->with('status', 'We have e-mailed your password reset link!');
    } else {
        // Tangani kasus jika token tidak berhasil dibuat
        return back()->withErrors(['email' => 'Failed to generate reset link. Please try again later.']);
    }
}


    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    protected function broker()
    {
        return Password::broker();
    }

    public function showforgetpassword()
    {
        return view('forgetpassword');
    }

//     public function showformforgetpassword(Request $request)
// {
//     // Validasi input email
//     $request->validate([
//         'email' => 'required|email|exists:users,email',
//     ]);

//     try {
//         // Panggil API untuk mendapatkan reset token
//         $response = Http::withHeaders([
//             'x-api-key' => self::API_KEY,
//         ])->post(self::API_URL . '/api/get_reset_token.json', [
//             'email' => $request->email,
//         ]);

//         // Ambil data dari respons API
//         $responseData = $response->json();
//         Log::info('Full Response Data: ' . json_encode($responseData));

//         if ($response->successful() && isset($responseData['token']) && isset($responseData['expires_date'])) {
//             // Simpan data token dan expired_date ke session
//             session([
//                 'reset_token' => $responseData['token'],
//                 'expires_at' => $responseData['expires_date'] ?? 'N/A', // Gunakan default jika tidak ada
//                 'email' => $request->email,
//                 'status' => $responseData['status'] ?? 'unknown', // Gunakan default jika tidak ada
//             ]);

//             // Logging untuk debug
//             Log::info('Generated Token: ' . session('reset_token'));
//             Log::info('Expires At: ' . session('expired_date'));
//             Log::info('Status: ' . session('status'));

//             // Kirim email untuk reset password
//             Mail::send('emails.forgotPassword', [
//                 'token' => session('reset_token'),
//                 'expired_date' => session('expired_date'),
//                 'status' => session('status'),
//             ], function($message) use ($request) {
//                 $message->to($request->email);
//                 $message->subject('Reset Password Notification');
//             });

//             // Kembali ke halaman sebelumnya dengan status
//             return back()->with('status', 'We have e-mailed your password reset link!');
//         } else {
//             // Tangani kasus jika token tidak berhasil dibuat
//             return back()->withErrors(['email' => 'Failed to generate reset link. Please try again later.']);
//         }
//     } catch (\Exception $e) {
//         // Logging untuk error
//         Log::error('Error during reset token generation: ' . $e->getMessage());

//         // Tampilkan pesan error umum
//         return back()->withErrors(['email' => 'An error occurred while processing your request. Please try again later.']);
//     }
// }





public function showResetPasswordForm($resetToken)
{
    return view('formforgetpassword');
}





public function submitResetPasswordForm(Request $request)
{
    $request->validate([
        'new_password' => 'required|min:6',
        'confirm_new_password' => 'required|same:new_password',
        'reset_token' => 'sometimes|required',
        'change_type' => 'required|in:reset',
    ]);

    $resetToken = session('reset_token');

    if (!$resetToken) {
        return back()->withErrors(['reset_token' => 'Reset token is required.'])->withInput();
    }

    // Panggil API sso.change_password dengan token yang sesuai
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => $resetToken,
    ])->post(self::API_URL . '/sso/change_password.json', [
        'password' => $request->new_password,
        'change_type' => 'reset',
    ]);

    $responseData = $response->json();


    if ($responseData['success']) {
        // Log respon untuk debug
        Log::info('Password reset API response:', [
            'status_code' => $response->status(),
            'response_body' => $response->body(),
            'success' =>  $responseData['success'],
        ]);

        Session::flash('success', 'Password reset successfully.');
        Log::info('Password reset successful for token:', ['token' => $resetToken]);
        return redirect()->route('login');


        // if ($varIsUse === true) {
        //     // Jika var_is_use FALSE, maka password berhasil diubah
        // } else {
        //     // Jika var_is_use TRUE, redirect ke halaman cantreset

        // }
    } else {
        // Penanganan error jika API gagal
        Log::error('Failed to reset password:', [
            'status_code' => $response->status(),
            'response_body' => $response->body(),
        ]);
        Log::warning('Attempt to use a used reset token:', ['token' => $resetToken]);
        return redirect()->route('cantreset')->withErrors([
            'error' => 'The reset token has already been used and cannot be reused.'
        ]);
    }
}






    public function login(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => self::API_KEY
            ])->post(self::API_URL . '/sso/login.json', [
                'username' => $request->email,
                'password' => $request->password,
            ]);
            Log::info('API request sent.', [
                'url' => self::API_URL . '/sso/login.json',
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);
            $responseData = $response->json();

            if ($response->successful() && isset($responseData['data']['access_token'])) {

                // Simpan access token ke session
                session(['access_token' => $responseData['data']['access_token']]);

                // Jika personal_info tersedia dalam respons, simpan data tersebut ke session juga
                if (isset($responseData['data']['personal_info'])) {
                    $personalInfo = $responseData['data']['personal_info'];
                    session([
                        'birthday' => $personalInfo['birthday'],
                        'full_name' => $personalInfo['full_name'],
                        'gender' => $personalInfo['gender'],
                        'phone' => $personalInfo['phone'],
                        'username' => $personalInfo['username'],
                        'email' => $request->email,
                        'profile_picture' => $personalInfo['profile_picture'] ?? null,// Simpan email ke session
                    ]);

                    // Simpan URL gambar profil ke session jika tersedia
                    if (isset($personalInfo['profile_picture'])) {
                        session(['profile_picture' => $personalInfo['profile_picture']]);
                    }
                    Log::info('Session data stored successfully.', [
                        'session_data' => session()->all()
                    ]);
                }

                return redirect()->route('dashboard');
            } elseif (isset($responseData['data']) && $responseData['result'] === 2) {
                return back()->withErrors([
                    'error' => $responseData['data'],
                ])->withInput();
            } elseif (isset($responseData['data']) && $responseData['result'] === 3) {
                return back()->withErrors([
                    'error' => $responseData['data'],
                ])->withInput();
            } elseif (isset($responseData['data']) && $responseData['result'] === 4) {
                return back()->withErrors([
                    'error' => $responseData['data'],
                ])->withInput();
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi kesalahan dalam melakukan permintaan HTTP
            return back()->withErrors([
                'error' => 'Something went wrong. Please try again later.'
            ])->withInput();
        }
    }


    public function index()
    {

        return view('welcome');
    }

    public function showdashboardadm()
    {
        return view('admin.dashboardadm');
    }

    public function dashboardadm()
    {

    }

    public function showCantreset()
    {
        return view('formcantreset');
    }

    public function organization()
    {
        return view('organization');
    }


    public function showaddorganization()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->get(self::API_URL . '/sso/list_organization_by_owner.json');

            if ($response->successful()) {
                $data = $response->json(); // Mengambil seluruh data dari respons
                $organizations = $data['data']['organizations']; // Mengambil data organisasi dari respons
                return view('organization', ['organizations' => $organizations]); // Mengirimkan data ke blade
            } else {
                return back()->with('error', 'Gagal mendapatkan daftar organisasi. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function showcreateorganization()
    {
        return view('addorganization');
    }

//     public function addorganization(Request $request)
// {
//     // Retrieve access token from session
//     $token = session('access_token');
//     $refreshToken = session('refresh_token'); // Ensure you store a refresh token if provided

//     if (!$token) {
//         Log::warning('Access token not found. Redirecting to login.');
//         return redirect()->route('login')->withErrors(['error' => 'Access token not found. Please login again.']);
//     }

//     // Validate input
//     $request->validate([
//         'organization_name' => 'required|string|max:255',
//         'description' => 'required|string|max:500',
//     ]);

//     Log::info('Attempting to add organization with name: ' . $request->organization_name);

//     try {
//         // Send a request to the API to add the organization
//         $response = Http::withHeaders([
//             'Authorization' => 'Bearer ' . $token,
//             'x-api-key' => self::API_KEY,
//         ])->post(self::API_URL . '/sso/create_organization.json', [
//             'organization_name' => $request->organization_name,
//             'description' => $request->description,
//         ]);

//         // Retrieve the response data
//         $data = $response->json();
//         Log::info('API Response:', $data);

//         if ($response->successful() && isset($data['success']) && $data['success'] === true) {
//             // Save verification token to session if present
//             if (isset($data['verification_token'])) {
//                 session(['verification_token' => $data['verification_token']]);
//             }

//             session(['organization_name' => $request->organization_name]);

//             Log::info('Organization added successfully: ' . $request->organization_name);

//             // Store a notification in the session
//             $notifications = session('notifications', []);
//             $notifications[] = [
//                 'title' => 'Organization Created Successfully',
//                 'message' => 'Your organization "' . $request->organization_name . '" has been created successfully. Please verify it using the provided token.',
//                 'verification_token' => session('verification_token'),
//             ];
//             session(['notifications' => $notifications]);

//             Log::info('Notification stored in the session.');

//             // Add success message with access token
//             return redirect('/organization')->with([
//                 'success_message' => 'Organization created successfully.',
//                 'access_token' => $token,
//             ]);
//         } else {
//             // Check if the token has expired and attempt to refresh if possible
//             if (isset($data['data']) && $data['data'] === 'Token expired' && $refreshToken) {
//                 Log::warning('Token expired. Attempting to refresh token.');

//                 // Attempt to refresh the token
//                 $refreshResponse = Http::post(self::API_URL . '/sso/refresh_token.json', [
//                     'refresh_token' => $refreshToken
//                 ]);

//                 $refreshData = $refreshResponse->json();

//                 if ($refreshResponse->successful() && isset($refreshData['access_token'])) {
//                     session(['access_token' => $refreshData['access_token']]);
//                     session(['refresh_token' => $refreshData['refresh_token']]); // Update the refresh token if provided
//                     return $this->addorganization($request); // Retry the request
//                 }

//                 // If unable to refresh, redirect to login
//                 return redirect()->route('login')->withErrors(['error' => 'Token expired. Please log in again.']);
//             }

//             Log::error('Failed to add organization. Response: ' . $response->body());
//             return back()->withErrors(['error_message' => 'Failed to add organization. Please try again.'])->withInput();
//         }
//     } catch (\Illuminate\Http\Client\RequestException $e) {
//         Log::error('HTTP Request failed: ' . $e->getMessage());
//         return back()->withErrors(['error_message' => 'HTTP Request failed: ' . $e->getMessage()])->withInput();
//     } catch (\Exception $e) {
//         Log::error('An error occurred: ' . $e->getMessage());
//         return back()->withErrors(['error_message' => 'Something went wrong, try again! ' . $e->getMessage()])->withInput();
//     }
// }


// public function addorganization(Request $request)
// {
//     // Validate request data
//     $request->validate([
//         'organization_name' => 'required|string|max:255',
//         'description' => 'required|string|max:500',
//     ]);

//     Log::info('Attempting to add organization with name: ' . $request->organization_name);

//     try {
//         // Send API request to create organization
//         $response = Http::withHeaders([
//             'Authorization' => 'Bearer ' . session('access_token'),
//             'x-api-key' => self::API_KEY,
//         ])->post(self::API_URL . '/sso/create_organization.json', [
//             'organization_name' => $request->organization_name,
//             'description' => $request->description,
//         ]);

//         // Log the response status and body for debugging
//         Log::info('Response Status: ' . $response->status());
//         Log::info('Response Body: ' . $response->body());

//         // Handle successful response
//         if ($response->successful()) {
//             $data = $response->json(); // Get data from the response

//             // Log the data structure
//             Log::info('Response Data: ' . print_r($data, true));

//             // Handle response based on actual structure
//             if (isset($data['success']) && $data['success']) {
//                 // Prepare notification data
//                 $notifications = session('notifications', []);
//                 $notifications[] = [
//                     'title' => 'Organization Created Successfully',
//                     'message' => 'Your organization "' . $request->organization_name . '" has been created successfully. Please verify it using the provided token.',
//                     'verification_token' => session('verification_token'), // Assuming verification_token is available in session
//                 ];
//                 session(['notifications' => $notifications]);

//                 // Log successful organization creation
//                 Log::info('Organization added successfully: ' . $request->organization_name);

//                 // Redirect or return view as needed
//                 return redirect()->route('organization.index')->with('success', 'Organization created successfully.');
//             } else {
//                 Log::error('Unexpected response format. Success flag is not set or false.');
//                 return back()->with('error', 'Failed to add organization. Please try again.');
//             }
//         } else {
//             // Log and handle error response
//             Log::error('Failed to add organization. Response: ' . $response->body());
//             return back()->with('error', 'Failed to add organization. Please try again.');
//         }
//     } catch (\Exception $e) {
//         // Log and handle exception
//         Log::error('Exception occurred while adding organization: ' . $e->getMessage());
//         return back()->with('error', $e->getMessage());
//     }
// }

public function addorganization(Request $request)
{
    // Validasi request data
    $request->validate([
        'organization_name' => 'required|string|max:255',
        'description' => 'required|string|max:500',
    ]);

    // Ambil access token dari session
    $token = session('access_token');

    // Periksa apakah token ada
    if (!$token) {
        return redirect()->route('login')->withErrors(['error' => 'Access token not found. Please login again.']);
    }

    Log::info('Attempting to add organization with name: ' . $request->organization_name);
    Log::info('Access Token Status: Token Available');

    try {
        // Kirim permintaan API untuk membuat organisasi
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token, // Gunakan format Bearer untuk Authorization
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/create_organization.json', [
            'organization_name' => $request->organization_name,
            'description' => $request->description,
        ]);

        // Log status respons dan body untuk debugging
        Log::info('Response Status: ' . $response->status());
        Log::info('Response Body: ' . $response->body());

        // Tangani respons yang sukses
        if ($response->successful()) {
            $data = $response->json(); // Ambil data dari respons

            // Log struktur data
            Log::info('Response Data: ' . print_r($data, true));

            // Tangani respons berdasarkan struktur yang sebenarnya
            if (isset($data['success']) && $data['success']) {
                // Siapkan data notifikasi
                $notifications = session('notifications', []);
                $notifications[] = [
                    'title' => 'Organization Created Successfully',
                    'message' => 'Your organization "' . $request->organization_name . '" has been created successfully. Please verify it using the provided token.',
                    'verification_token' => session('verification_token'), // Mengasumsikan verification_token tersedia di sesi
                ];
                session(['notifications' => $notifications]);

                // Log pembuatan organisasi yang sukses
                Log::info('Organization added successfully: ' . $request->organization_name);

                // Redirect atau kembalikan view sesuai kebutuhan
                return redirect()->route('organization.index')->with('success', 'Organization created successfully.');
            } else {
                Log::error('Unexpected response format. Success flag is not set or false.');
                return back()->with('error', 'Failed to add organization. Please try again.');
            }
        } else {
            // Log dan tangani respons kesalahan
            Log::error('Failed to add organization. Response: ' . $response->body());
            return back()->with('error', 'Failed to add organization. Please try again.');
        }
    } catch (\Exception $e) {
        // Log dan tangani pengecualian
        Log::error('Exception occurred while adding organization: ' . $e->getMessage());
        return back()->with('error', $e->getMessage());
    }
}


    public function showvieworganization($organization_name)
    {
        Log::info('Attempting to view organization with name: ' . $organization_name);

        try {
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->get(self::API_URL . '/sso/list_organization_by_owner.json');

            if ($response->successful()) {
                $data = $response->json(); // Mengambil seluruh data dari respons
                $organizations = $data['data']['organizations']; // Mengambil data organisasi dari respons

                // Cari organisasi yang sesuai dengan nama yang diberikan
                foreach ($organizations as $org) {
                    if ($org['organization_name'] === $organization_name) {
                        $organization = [
                            'organization_name' => $organization_name,
                            'description' => $org['description']
                        ];
                        Log::info('Organization found: ' . $organization_name);
                        return view('vieworganization', compact('organization'));
                    }
                }

                Log::warning('Organization not found: ' . $organization_name);
                return back()->with('error', 'Organization not found');
            } else {
                Log::error('Failed to get organization list. Response: ' . $response->body());
                return back()->with('error', 'Failed to get organization list. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while viewing organization: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
    public function showmoredetails($organization_name)
    {
        Log::info('Attempting to show more details for organization: ' . $organization_name);

        try {
            // Memanggil showvieworganization untuk mendapatkan data organisasi
            $organization = $this->showvieworganization($organization_name)->getData()['organization'];

            // Mengirim data organisasi dan nama organisasi ke tampilan moredetails
            Log::info('Showing more details for organization: ' . $organization_name);
            return view('moredetails', compact('organization', 'organization_name'));
        } catch (\Exception $e) {
            Log::error('Exception occurred while showing more details: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function showeditorganization($organization_name)
    {
        Log::info('Attempting to edit organization: ' . $organization_name);

        try {
            // Memanggil showvieworganization untuk mendapatkan data organisasi
            $organization = $this->showvieworganization($organization_name)->getData()['organization'];

            // Mengirim data organisasi ke tampilan editorganization
            Log::info('Showing edit view for organization: ' . $organization_name);
            return view('editorganization', compact('organization'));
        } catch (\Exception $e) {
            Log::error('Exception occurred while showing edit view: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function editorganization(Request $request)
    {

    }


    public function personal()
    {
        $personalInfo = [
            'fullname' => session('full_name'),
            'username' => session('username'),
            'dateofbirth' => session('birthday'),
            'gender' => session('gender'),
            'email' => session('email'),
            'phone' => session('phone'),
        ];

        return view('personal', compact('personalInfo'));

    }


    public function showeditpersonal()
    {
        return view('editpersonal');
    }

    public function editpersonal(Request $request)
{
    // Mengirim data ke endpoint menggunakan HTTP Client
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => session('access_token'),
    ])->post(self::API_URL . '/sso/update_personal_info.json', [
        'fullname' => $request->fullname,
        'username' => $request->username,
        'birthday' => $request->dateofbirth,
        'phone' => $request->phone,
        'gender' => $request->gender == 0 ? 0 : 1,
    ]);

    // Log the response status and body
    Log::info('API Response:', [
        'status' => $response->status(),
        'body' => $response->body()
    ]);

    // Cek respon dari endpoint dan sesuaikan tindakan berikutnya
    if ($response->successful()) {
        // Jika response berhasil, perbarui session dengan data yang baru
        session([
            'full_name' => $request->fullname,
            'username' => $request->username,
            'birthday' => $request->dateofbirth,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);

        return redirect('/personal')->with('success', 'Data has been saved!');
    } else {
        // Jika gagal, kembalikan pengguna dengan pesan error
        return redirect()->back()->with('error', 'Failed to save data! Please try again.');
    }
}

    public function showuploadProfilePicture()
    {
        return view ('uploadprofile');
    }

    public function uploadProfilePicture(Request $request)
    {
        Log::info('Starting profile picture upload process.');

        // Validasi file gambar
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();

            Log::info('File uploaded by user:', ['filename' => $filename]);

            $requestData = [
                'profile_picture' => $file,
            ];

            try {
                $response = Http::withHeaders([
                    'Authorization' => session('access_token'),
                    'x-api-key' => self::API_KEY,
                ])->attach('profile_picture', $file->getPathname(), $file->getClientOriginalName())
                ->post(self::API_URL . '/sso/update_profile_picture.json', $requestData);

                $data = $response->json();

                Log::info('API response received:', ['status' => $response->status(), 'data' => $data]);

                if ($response->successful()) {
                    $file->storeAs('public/profile_pictures', $filename);
                    session(['profile_picture' => 'storage/profile_pictures/' . $filename]);

                    echo "<script>localStorage.setItem('profile_picture', 'storage/profile_pictures/$filename');</script>";

                    Log::info('Profile picture stored successfully.', ['filename' => $filename]);

                    return redirect()->route('personal')->with('success', 'Profile picture uploaded successfully.');
                } else {
                    $errorMessage = $data['message'] ?? 'An error occurred while uploading profile picture.';
                    Log::error('API error:', ['message' => $errorMessage]);
                    return redirect()->back()->with('error', $errorMessage);
                }
            } catch (\Exception $e) {
                Log::error('Exception occurred during API request:', ['exception' => $e->getMessage()]);
                return redirect()->back()->with('error', 'An error occurred during the upload process.');
            }
        } else {
            Log::warning('No file uploaded by user.');
            return redirect()->back()->with('error', 'No file uploaded.');
        }
    }





    public function showsecurity()
    {
        return view ('security');
    }

    public function showeditpassword()
    {
        return view ('editpassword');
    }

    public function editpassword(Request $request)
{
    // Validasi input
    $request->validate([
        'new_password' => 'required|min:6',
        'confirm_new_password' => 'required|same:new_password',
    ]);

    // Ambil access token dari session
    $token = session('access_token');

    if (!$token) {
        return redirect()->route('login')->withErrors(['error' => 'Access token not found. Please login again.']);
    }

    // Panggil API untuk change password
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => $token,
    ])->post(self::API_URL . '/sso/change_password.json', [
        'password' => $request->new_password,
    ]);

    // Cek apakah respon API sukses
    if ($response->successful()) {
        // Logout user setelah perubahan password berhasil
        Auth::logout();
        Session::flash('success', 'Password changed successfully. Please log in again.');
        return redirect()->route('confirm-logout'); // Redirect ke halaman logout view
    } else {
        // Tangani kesalahan dari API
        $errorMessages = $response->json();
        if ($response->status() == 422) {
            return back()->withErrors($errorMessages)->withInput();
        } else {
            return back()->withErrors(['error' => 'Failed to process password change. Please try again later.'])->withInput();
        }
    }
}





    public function confirmlogout()
    {
        return view('confirmLogout');

    }

    public function logout()
{
    // Ambil token akses dari sesi
    $access_token = session('access_token');

    if ($access_token) {
        // Kirim permintaan logout ke API menggunakan HTTP client dengan menggunakan access token
        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/logout.json');

        // Log the response status and body
        Log::info('Logout API Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers()
        ]);

        // Periksa apakah permintaan logout berhasil
        if ($response->successful() && $response['success']) {
            // Log successful logout
            Log::info('User logged out successfully.', ['user' => session('username')]);

            // Hapus hanya token akses dari sesi
            session()->flush();

            // Redirect ke halaman login atau halaman lain yang sesuai setelah logout
            return redirect()->route('login')->with('success', 'Logout successful.');
        } else {
            // Log failed logout attempt
            Log::warning('Failed to logout.', [
                'user' => session('username'),
                'response_body' => $response->body()
            ]);

            // Jika permintaan logout gagal atau respons tidak mengandung kunci 'success' yang bernilai true, tampilkan pesan kesalahan atau lakukan penanganan yang sesuai
            return back()->withError('Failed to logout. Please try again.');
        }
    } else {
        // Log the case where no access token was found in the session
        Log::info('No access token found in session during logout attempt.');

        // Jika access token tidak ada, langsung redirect ke halaman login
        return redirect()->route('login');
    }
}





    public function showaccess($role)
    {
        return view ('admin.access', ['role' => $role]);
    }

    public function showdetailsadm()
    {

    }

    public function showedituser()
    {
        return view ('edituser');
    }

    public function personaladm()
    {
        return view ('admin.detailsadm');
    }

    public function showeditpersonaladm()
    {
        return view ('admin.editpersonaladm');
    }

    public function editpersonaladm()
    {

    }

    public function showsecurityadm()
    {
        return view ('admin.securityadm');
    }

    public function showchangepwadm()
    {
        return view ('admin.changepwadm');
    }

    public function showedituseradm()
    {
        return view ('admin.edituseradm');
    }

    public function edituseradm()
    {

    }



    public function redirectToGoogle()
    {
        // Redirect pengguna ke halaman autentikasi Google menggunakan Socialite
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Ambil informasi pengguna dari Google setelah autentikasi
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            // Tangkap dan tampilkan pesan kesalahan jika autentikasi gagal
            dd($e->getMessage());
        }

        // Periksa apakah pengguna berhasil diambil dari Google
        if ($googleUser) {
            // Ambil alamat email pengguna dari data yang diterima dari Google
            $email = $googleUser->email;

            // Kirim permintaan registrasi pengguna ke backend menggunakan HTTP Client
            $response = Http::withHeaders([
                'x-api-key' => self::API_KEY, // Header x-api-key untuk otentikasi pada API backend
            ])->post(self::API_URL . '/sso/register.json', [
                'email' => $email, // Kirim alamat email pengguna untuk registrasi
                'password' => 'password_default', // Tambahkan kata sandi default untuk registrasi
            ]);

            // Periksa apakah permintaan registrasi berhasil
            if ($response->successful()) {
                // Jika berhasil, arahkan pengguna ke halaman dashboard
                return redirect()->route('dashboard');
            } else {
                // Jika gagal, arahkan pengguna kembali ke halaman registrasi dengan pesan kesalahan
                return redirect()->route('register')->with('error', 'Gagal melakukan registrasi. Silakan coba lagi.');
            }

        } else {
            // Jika gagal mengambil informasi pengguna dari Google, tampilkan pesan kesalahan
            dd('Failed to retrieve user information from Google.');
        }
    }
}
