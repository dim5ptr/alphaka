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
                Mail::send('emails/forgotpassword', ['token' => session('reset_token')], function($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password Notification');
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

public function active($token)
{
    try {
        // Mengirim permintaan ke API untuk memverifikasi token
        $response = Http::withHeaders([
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/user_verify', [
            'activation_key' => $token,
        ]);

        $data = $response->json();

        // Memeriksa apakah respons API sukses
        if ($response->successful() && $data['success']) {
            // Jika berhasil, arahkan ke halaman sukses
            return view('registersuccessful');
        } else {
            // Jika tidak berhasil, arahkan ke halaman kesalahan dengan pesan dari API
            return redirect('/')->withErrors([
                'error_message' => $data['data'],
            ]);
        }

    } catch (\Illuminate\Http\Client\RequestException $e) {
        return redirect('/')->withErrors([
            'error_message' => 'HTTP Request failed: ' . $e->getMessage(),
        ]);
    } catch (\Exception $e) {
        return redirect('/')->withErrors([
            'error_message' => 'Something went wrong, try again! ' . $e->getMessage(),
        ]);
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
        return view('auth.passwords.email');
    }
//ganti email please untuk yg dibwah ino ke api
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

    public function editforgetpassword()
    {

    }

    public function showformforgetpassword(Request $request)
{
    // Validasi input email
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    try {
        // Panggil API untuk mendapatkan reset token
        $response = Http::withHeaders([
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/api/get_reset_token.json', [
            'email' => $request->email,
        ]);

        // Ambil data dari respons API
        $responseData = $response->json();
        Log::info('Full Response Data: ' . json_encode($responseData));

        if ($response->successful() && isset($responseData['token']) && isset($responseData['expires_date'])) {
            // Simpan data token dan expired_date ke session
            session([
                'reset_token' => $responseData['token'],
                'expires_at' => $responseData['expires_date'] ?? 'N/A', // Gunakan default jika tidak ada
                'email' => $request->email,
                'status' => $responseData['status'] ?? 'unknown', // Gunakan default jika tidak ada
            ]);

            // Logging untuk debug
            Log::info('Generated Token: ' . session('reset_token'));
            Log::info('Expires At: ' . session('expired_date'));
            Log::info('Status: ' . session('status'));

            // Kirim email untuk reset password
            Mail::send('emails.forgotPassword', [
                'token' => session('reset_token'),
                'expired_date' => session('expired_date'),
                'status' => session('status'),
            ], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password Notification');
            });

            // Kembali ke halaman sebelumnya dengan status
            return back()->with('status', 'We have e-mailed your password reset link!');
        } else {
            // Tangani kasus jika token tidak berhasil dibuat
            return back()->withErrors(['email' => 'Failed to generate reset link. Please try again later.']);
        }
    } catch (\Exception $e) {
        // Logging untuk error
        Log::error('Error during reset token generation: ' . $e->getMessage());

        // Tampilkan pesan error umum
        return back()->withErrors(['email' => 'An error occurred while processing your request. Please try again later.']);
    }
}





    public function showResetPasswordForm($token)
    {
        // Token disediakan dari API dan disimpan di session
         return view('formforgetpassword', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
{
    $request->validate([
        'new_password' => 'required|min:6',
        'confirm_new_password' => 'required|same:new_password',
    ]);

    $resetToken = $request->token; // Token yang dikirim melalui URL atau inputan

    // Membuat request untuk memeriksa status token
    $tokenStatusResponse = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => 'Bearer ' . $resetToken,
    ])->get(self::API_URL . '/sso/token_status.json');

    if ($tokenStatusResponse->successful()) {
        $tokenStatus = $tokenStatusResponse->json();

        if ($tokenStatus['is_use']) {
            // Token sudah digunakan, kembalikan pesan error
            return back()->withErrors(['error' => 'Token has already been used. Please request a new one.'])->withInput();
        }
    } else {
        // Tangani kesalahan validasi atau kesalahan lain dari API token status
        return back()->withErrors(['error' => 'Failed to verify token. Please try again later.'])->withInput();
    }

    // Membuat request ke API untuk reset password dengan token di header
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => 'Bearer ' . $resetToken, // Mengirim token reset password di header
    ])->post(self::API_URL . '/sso/change_password.json', [
        'password' => $request->new_password,
        'change_type' => 'reset',  // Menyatakan bahwa ini adalah reset password
    ]);

    if ($response->successful()) {
        // Reset password berhasil
        return redirect()->route('login')->with('success', 'Password reset successfully. Please log in with your new password.');
    } else {
        // Tangani kesalahan validasi atau kesalahan lain dari API
        $errorMessages = $response->json();
        if ($response->status() == 422) {
            return back()->withErrors($errorMessages)->withInput();
        } else {
            return back()->withErrors(['error' => 'Failed to reset password. Please try again later.'])->withInput();
        }
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
                        'email' => $request->email, // Simpan email ke session
                    ]);

                    // Simpan URL gambar profil ke session jika tersedia
                    if (isset($personalInfo['profile_picture'])) {
                        session(['profile_picture' => $personalInfo['profile_picture']]);
                    }
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


    // public function showcreateorganization()
    // {
    //     return view('addorganization');
    // }


    // public function addorganization(Request $request)
    // {
    //     try {
    //         $response = Http::withHeaders([
    //             'Authorization' => session('access_token'),
    //             'x-api-key' => self::API_KEY,
    //         ])->post(self::API_URL . '/sso/create_organization', [
    //             'organization_name' => $request->organization_name,
    //             'description' => $request->description,
    //         ]);

    //         if ($response->successful()) {
    //             return view('organization', ['organizations' => $organizations]); // Mengirimkan data ke blade
    //         } else {
    //             return back()->with('error', 'Failed to get organization list. Please try again.');
    //         }
    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    // public function showvieworganization($organization_name)
    // {
    //     try {
    //         $response = Http::withHeaders([
    //             'Authorization' => session('access_token'),
    //             'x-api-key' => self::API_KEY,
    //         ])->get(self::API_URL . '/sso/list_organization_by_owner.json');

    //         if ($response->successful()) {
    //             $data = $response->json(); // Mengambil seluruh data dari respons
    //             $organizations = $data['data']['organizations']; // Mengambil data organisasi dari respons

    //             // Cari organisasi yang sesuai dengan nama yang diberikan
    //             foreach ($organizations as $org) {
    //                 if ($org['organization_name'] === $organization_name) {
    //                     $organization = [
    //                         'organization_name' => $organization_name,
    //                         'description' => $org['description']
    //                     ];
    //                     return view('vieworganization', compact('organization'));
    //                 }
    //             }

    //             // Jika organisasi tidak ditemukan
    //             return back()->with('error', 'Organization not found');
    //         } else {
    //             return back()->with('error', 'Failed to get organization list. Please try again.');
    //         }

    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    // public function showmoredetails($organization_name)
    // {
    //     try {
    //         // Memanggil showvieworganization untuk mendapatkan data organisasi
    //         $organization = $this->showvieworganization($organization_name)->getData()['organization'];

    //         // Mengirim data organisasi dan nama organisasi ke tampilan moredetails
    //         return view('moredetails', compact('organization', 'organization_name'));
    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }


    // public function showeditorganization($organization_name)
    // {
    //     // Memanggil showvieworganization untuk mendapatkan data organisasi
    //     $organization = $this->showvieworganization($organization_name)->getData()['organization'];

    //     // Mengirim data organisasi ke tampilan editorganization
    //     return view('editorganization', compact('organization'));
    // }


    // public function editorganization(Request $request)
    // {

    // }


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
            'gender' => $request->gender == 'Male' ? 1 : 0,
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
    // Validasi file gambar
    $request->validate([
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $filename = time() . '_' . $file->getClientOriginalName();

        $requestData = [
            'profile_picture' => $file,
        ];

        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->attach('profile_picture', $file->getPathname(), $file->getClientOriginalName())
        ->post(self::API_URL . '/sso/update_profile_picture.json', $requestData);

        $data = $response->json();

        if ($response->successful()) {
            $file->storeAs('public/profile_pictures', $filename);
            session(['profile_picture' => 'storage/profile_pictures/' . $filename]);

            echo "<script>localStorage.setItem('profile_picture', 'storage/profile_pictures/$filename');</script>";

            return redirect()->route('personal')->with('success', 'Profile picture uploaded successfully.');
        } else {
            $errorMessage = $data['message'] ?? 'An error occurred while uploading profile picture.';
            return redirect()->back()->with('error', $errorMessage);
        }
    } else {
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
        $request->validate([
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|same:new_password',
            'reset_token' => 'sometimes|required', // Validasi token reset jika ada
            'request_type' => 'required|in:change,reset', // Flag untuk menentukan jenis permintaan
        ]);

        // Cek jenis permintaan
        $isResetRequest = $request->request_type === 'reset';

        if ($isResetRequest) {
            // Logika untuk reset password menggunakan token reset
            $resetToken = session('reset_token'); // Ambil token dari request

            if (!$resetToken) {
                return back()->withErrors(['reset_token' => 'Reset token is required for reset password.'])->withInput();
            }

            Log::info('Reset Token: ' . $resetToken);
            Log::info('New Password: ' . $request->new_password);

            // Panggil API untuk reset password
            $response = Http::withHeaders([
                'x-api-key' => self::API_KEY,
                'Authorization' => $resetToken, // Menambahkan token ke header
            ])->post(self::API_URL . '/sso/change_password.json', [
                'password' => $request->new_password,
                'change_type' => 'reset', // Tambahkan change_type di sini
            ]);

            if ($response->successful()) {
                // Password berhasil direset
                Session::flash('success', 'Password reset successfully.'); // Pesan sukses
                return redirect()->route('login'); // Redirect ke halaman login
            } else {
                // Tangani kesalahan dari API
                $errorMessages = $response->json();
                if ($response->status() == 422) {
                    return back()->withErrors($errorMessages)->withInput();
                } else {
                    return back()->withErrors(['error' => 'Failed to reset password. Please try again later.'])->withInput();
                }
            }
        } else {
            // Logika untuk perubahan password biasa
            $accessToken = session('access_token');

            if (!$accessToken) {
                return redirect()->route('login')->withErrors(['error' => 'Access token not found. Please login again.']);
            }

            // Panggil API untuk mengubah password
            $response = Http::withHeaders([
                'Authorization' => $accessToken,
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/sso/change_password.json', [
                'password' => $request->new_password,
                'change_type' => 'change', // Tambahkan change_type di sini
            ]);

            if ($response->successful()) {
                // Password berhasil diubah
                Auth::logout(); // Logout pengguna
                Session::flash('success', 'Password changed successfully. Please log in again.'); // Pesan sukses
                return redirect()->route('login'); // Redirect ke halaman login
            } else {
                // Tangani kesalahan dari API
                $errorMessages = $response->json();
                if ($response->status() == 422) {
                    return back()->withErrors($errorMessages)->withInput();
                } else {
                    return back()->withErrors(['error' => 'Failed to change password. Please try again later.'])->withInput();
                }
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

            // Periksa apakah permintaan logout berhasil
            if ($response->successful() && $response['success']) {
                // Hapus hanya token akses dari sesi
                session()->flush();

                // Redirect ke halaman login atau halaman lain yang sesuai setelah logout
                return redirect()->route('login')->with('success', 'Logout successful.');
            } else {
                // Jika permintaan logout gagal atau respons tidak mengandung kunci 'success' yang bernilai true, tampilkan pesan kesalahan atau lakukan penanganan yang sesuai
                // Misalnya:
                return back()->withError('Failed to logout. Please try again.');
            }
        } else {
            // Jika access token tidak ada, langsung redirect ke halaman login
            return redirect()->route('login');
        }
    }


    public function sendEmail()
    {
        // Define variables
        $introLines = ['If you request us to reset your account password, here is the link you can use!'];
        $actionText = 'Reset Password Form';
        $actionUrl = 'https://127.0.0.1:8000/password/reset?token=abc123';
        $displayableActionUrl = 'https://127.0.0.1:8000/password/reset?token=abc123';
        $outroLines = ['This password reset link will expire in 60 minutes. if you did not request a password reset, no further action is required.'];
        $salutation = 'Sarastya';

        // Send email
        Mail::send([], [], function ($message) use ($greeting, $introLines, $actionText, $actionUrl, $displayableActionUrl, $outroLines, $salutation) {
            $message->to('recipient@example.com')
                    ->subject('Welcome to Our Service')
                    ->setBody(view('emails.custom', compact('greeting', 'introLines', 'actionText', 'actionUrl', 'displayableActionUrl', 'outroLines', 'salutation'))->render(), 'text/html');
        });

        return 'Email sent successfully!';
    }


    // public function showaccess($role)
    // {
    //     return view ('admin.access', ['role' => $role]);
    // }

    // public function showdetailsadm()
    // {

    // }

    // public function showedituser()
    // {
    //     return view ('edituser');
    // }

    // public function personaladm()
    // {
    //     return view ('admin.detailsadm');
    // }

    // public function showeditpersonaladm()
    // {
    //     return view ('admin.editpersonaladm');
    // }

    // public function editpersonaladm()
    // {

    // }

    // public function showsecurityadm()
    // {
    //     return view ('admin.securityadm');
    // }

    // public function showchangepwadm()
    // {
    //     return view ('admin.changepwadm');
    // }

    // public function showedituseradm()
    // {
    //     return view ('admin.edituseradm');
    // }

    // public function edituseradm()
    // {

    // }



    // public function redirectToGoogle()
    // {
    //     // Redirect pengguna ke halaman autentikasi Google menggunakan Socialite
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         // Ambil informasi pengguna dari Google setelah autentikasi
    //         $googleUser = Socialite::driver('google')->user();
    //     } catch (\Exception $e) {
    //         // Tangkap dan tampilkan pesan kesalahan jika autentikasi gagal
    //         dd($e->getMessage());
    //     }

    //     // Periksa apakah pengguna berhasil diambil dari Google
    //     if ($googleUser) {
    //         // Ambil alamat email pengguna dari data yang diterima dari Google
    //         $email = $googleUser->email;

    //         // Kirim permintaan registrasi pengguna ke backend menggunakan HTTP Client
    //         $response = Http::withHeaders([
    //             'x-api-key' => self::API_KEY, // Header x-api-key untuk otentikasi pada API backend
    //         ])->post(self::API_URL . '/sso/register.json', [
    //             'email' => $email, // Kirim alamat email pengguna untuk registrasi
    //             'password' => 'password_default', // Tambahkan kata sandi default untuk registrasi
    //         ]);

    //         // Periksa apakah permintaan registrasi berhasil
    //         if ($response->successful()) {
    //             // Jika berhasil, arahkan pengguna ke halaman dashboard
    //             return redirect()->route('dashboard');
    //         } else {
    //             // Jika gagal, arahkan pengguna kembali ke halaman registrasi dengan pesan kesalahan
    //             return redirect()->route('register')->with('error', 'Gagal melakukan registrasi. Silakan coba lagi.');
    //         }

    //     } else {
    //         // Jika gagal mengambil informasi pengguna dari Google, tampilkan pesan kesalahan
    //         dd('Failed to retrieve user information from Google.');
    //     }
    // }
}
