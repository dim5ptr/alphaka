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
use App\Mail\VerifAddMember; // Add this line





class HttpController extends Controller
{
    const API_URL = 'http://192.168.1.24:14041/api';
    const API_KEY = '5af97cb7eed7a5a4cff3ed91698d2ffb';
    private static $access_token = null;

    public function clearNotifications(Request $request)
    {
        // Clear notifications from the session
        session()->forget('notifications');

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Notifications cleared successfully.');
    }

    public function inbox(Request $request)
    {
        // Your logic for the inbox functionality
        return view('inbox'); // Return the view for inbox or perform other actions
    }

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
        // API call to list organizations by owner
        $ownerResponse = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/sso/list_organization_by_owner.json');

        // API call to list organizations by member
        $memberResponse = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => '5af97cb7eed7a5a4cff3ed91698d2ffb',
        ])->post(self::API_URL . '/sso/list_organization_by_member.json');

        if ($ownerResponse->successful() && $memberResponse->successful()) {
            $ownerData = $ownerResponse->json();
            $memberData = $memberResponse->json();

            // Extract organization data and add type to differentiate between owner and member
            $ownerOrganizations = array_map(function ($org) {
                $org['type'] = 'owner';
                return $org;
            }, $ownerData['data']['organizations'] ?? []);

            $memberOrganizations = array_map(function ($org) {
                $org['type'] = 'member';
                return $org;
            }, $memberData['data']['organizations'] ?? []);

            // Merge the two organization arrays
            $organizations = array_merge($ownerOrganizations, $memberOrganizations);

            // Pass the merged data to the Blade view
            return view('organization', ['organizations' => $organizations]);
        } else {
            return back()->with('error', 'Gagal mendapatkan daftar organisasi. Silakan coba lagi.');
        }

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

    // public function showaddorganization()
    // {
    //     try {
    //         $response = Http::withHeaders([
    //             'Authorization' => session('access_token'),
    //             'x-api-key' => self::API_KEY,
    //         ])->get(self::API_URL . '/sso/list_organization_by_owner.json');

    //         if ($response->successful()) {
    //             $data = $response->json(); // Mengambil seluruh data dari respons
    //             $organizations = $data['data']['organizations']; // Mengambil data organisasi dari respons
    //             return view('organization', ['organizations' => $organizations]); // Mengirimkan data ke blade
    //         } else {
    //             return back()->with('error', 'Gagal mendapatkan daftar organisasi. Silakan coba lagi.');
    //         }

    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }


    public function showcreateorganization()
    {
        return view('addorganization');
    }

public function addOrganization(Request $request)
{
    // Validate input
    $request->validate([
        'organization_name' => 'required|string|max:255',
        'description' => 'required|string|max:500',
    ]);

    Log::info('Attempting to add organization with name: ' . $request->organization_name);

    try {
        // Send a request to the API to add the organization
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/create_organization.json', [
            'organization_name' => $request->organization_name,
            'description' => $request->description,
        ]);

        // Retrieve the response data
        $data = $response->json();
        Log::info('API Response:', $data);

        // Check if the response is successful
        if ($response->successful() && isset($data['success']) && $data['success'] === true) {
            Log::info('Organization added successfully: ' . $request->organization_name);

            // Check for activation key
            if (isset($data['activation_key'])) {
                // Save verification token in session
                session(['activation_key' => $data['activation_key']]);
                Log::info('Activation key: ' . $data['activation_key']);
            } else {
                Log::warning('No activation key provided in the response.');
            }

            // Save organization name in session
            session(['organization_name' => $request->organization_name]);

            // Send notification email
            Log::info('Preparing to send organization creation email');
            Mail::send('emails.verify-organization', [
                'organization' => $request->organization_name,
                'token' => session('activation_key') ?? 'No token available' // Fallback if activation_key is missing
            ], function ($message) use ($request) {
                $message->to(session('email'));
                $message->subject('Organization Created Successfully');
            });
            Log::info('Organization creation email sent.');

            return redirect('/organization')->with('success_message', 'Organization created successfully.');
        } else {
            // Log error and show error message
            Log::error('Failed to add organization. Response: ' . $response->body());
            return back()->withErrors(['error_message' => 'Failed to add organization. Please try again.'])->withInput();
        }
    } catch (\Illuminate\Http\Client\RequestException $e) {
        Log::error('HTTP Request failed: ' . $e->getMessage());
        return back()->withErrors(['error_message' => 'HTTP Request failed: ' . $e->getMessage()])->withInput();
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return back()->withErrors(['error_message' => 'Something went wrong, try again! ' . $e->getMessage()])->withInput();
    }
}
    public function showAddMember($id)
    {
        return view('addmember', ['organization_id' => $id]);
    }

    // Add member to the organization
    public function addMember(Request $request, $id)
    {
        // Validate the request input (search term)
        $request->validate([
            'search' => 'required|string',
        ]);

        // API request to search for users
        try {
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/sso/list_user.json', [
                'find' => $request->input('search'),
            ]);

            $data = $response->json();

            // Log the response for debugging
            Log::info('List User API Response:', ['response' => $data]);

            // Check if API response is successful
            if ($response->successful()) {
                // Return view with search results
                return view('organization.add_member', [
                    'organization_id' => $id,
                    'users' => $data['users'] ?? [],
                ]);
            } else {
                return back()->withErrors('Failed to fetch users. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching users:', ['message' => $e->getMessage()]);
            return back()->withErrors('An error occurred while fetching users.');
        }
    }

    // Add selected member to the organization
    public function addMemberToOrganization(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        // API request to add a member
        try {
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/sso/add_member_organization.json', [
                'organization_id' => $id,
                'user_ids' => $request->input('user_id'),
            ]);

            $data = $response->json();

            // Log the response for debugging
            Log::info('Add Member API Response:', ['response' => $data]);

            if ($response->successful()) {
                return redirect()->route('showvieworganization', $id)
                    ->with('success', 'Member added successfully.');
            } else {
                return back()->withErrors('Failed to add member. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Error adding member to organization:', ['message' => $e->getMessage()]);
            return back()->withErrors('An error occurred while adding the member.');
        }
    }

public function organizationVerify(Request $request, $token)
{
    // Log for debugging
    Log::info('Route accessed with token: ' . $token);

    try {
        // Send request to the API for organization verification
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),  // Use access token from session
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/organization_verify.json', [
            'activation_key' => $token,  // Use token from the URL
        ]);

        // Retrieve the response data
        $data = $response->json();
        Log::info('Activation API Response:', $data);

        // Check if the response is successful
        if ($response->successful() && $data['success']) {
            // Organization activated successfully
            return redirect('/organization')->with('success_message', 'Your organization has been activated successfully.');
        } else {
            // Log error and show error message
            Log::error('Failed to activate organization. Response: ' . $response->body());
            return back()->withErrors(['error_message' => 'Failed to activate your organization. Please try again.'])->withInput();
        }
    } catch (\Illuminate\Http\Client\RequestException $e) {
        Log::error('HTTP Request failed: ' . $e->getMessage());
        return back()->withErrors(['error_message' => 'HTTP Request failed: ' . $e->getMessage()])->withInput();
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return back()->withErrors(['error_message' => 'Something went wrong, try again! ' . $e->getMessage()])->withInput();
    }
}




    // public function showvieworganization($organization_name)
    // {
    //     Log::info('Attempting to view organization with name: ' . $organization_name);

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
    //                         'organization_id' => $org['id'],
    //                         'organization_name' => $organization_name,
    //                         'description' => $org['description'],
    //                         'members_count' => $org['members_count'] ?? 0
    //                     ];
    //                     Log::info('Organization found: ' . $organization_name);
    //                     return view('vieworganization', compact('organization'));
    //                 }
    //             }

    //             Log::warning('Organization not found: ' . $organization_name);
    //             return back()->with('error', 'Organization not found');
    //         } else {
    //             Log::error('Failed to get organization list. Response: ' . $response->body());
    //             return back()->with('error', 'Failed to get organization list. Please try again.');
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Exception occurred while viewing organization: ' . $e->getMessage());
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    public function showvieworganization($organization_name)
{
    Log::info('Attempting to view organization with name: ' . $organization_name);

    try {
        // API call to list organizations by owner
        $ownerResponse = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/sso/list_organization_by_owner.json');

        // API call to list organizations by member
        $memberResponse = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => '5af97cb7eed7a5a4cff3ed91698d2ffb',
        ])->post(self::API_URL . '/sso/list_organization_by_member.json');

        // Check if both API responses are successful
        if ($ownerResponse->successful() && $memberResponse->successful()) {
            // Parse the responses
            $ownerData = $ownerResponse->json();
            $memberData = $memberResponse->json();

            // Get the organizations from both responses
            $ownerOrganizations = $ownerData['data']['organizations'] ?? [];
            $memberOrganizations = $memberData['data']['organizations'] ?? [];

            // Combine both organizations
            $organizations = array_merge($ownerOrganizations, $memberOrganizations);

            // Search for the organization by name
            foreach ($organizations as $org) {
                Log::info('Organization data being processed:', $org); // Log the entire organization data

                if ($org['organization_name'] === $organization_name) {
                    // Log the organization name we're attempting to view
                    Log::info('Attempting to view organization with name: ' . $organization_name);

                    // Organization found, prepare data for the view
                    $organization = [
                        'organization_id' => $org['id'],
                        'organization_name' => $organization_name,
                        'description' => $org['description'],
                        'members_count' => $org['members_count'] ?? 0,
                    ];

                    // Log the prepared organization data
                    Log::info('Prepared organization data:', $organization);

                    return view('vieworganization', compact('organization'));
                }
            }



            // If no matching organization is found
            Log::warning('Organization not found: ' . $organization_name);
            return back()->with('error', 'Organization not found');
        } else {
            // Log error if either API fails
            Log::error('Failed to get organization list. Owner Response: ' . $ownerResponse->body() . ', Member Response: ' . $memberResponse->body());
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
    // Validate input
    $request->validate([
        'organization_id' => 'required|integer',
        'organization_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
    ]);

    Log::info('Attempting to update organization ID: ' . $request->organization_id);

    try {
        // Send a request to update the organization
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/update_organization.json', [
            'organization_id' => $request->organization_id,
            'parent_id' => null, // Adjust as necessary
            'organization_name' => $request->organization_name,
            'description' => $request->description,
        ]);

        // Check the response
        if ($response->successful() && $response->json('success')) {
            Log::info('Organization updated successfully: ' . $request->organization_id);
            return redirect('/organization')->with('success_message', 'Organization updated successfully.');
        } else {
            Log::error('Failed to update organization. Response: ' . $response->body());
            return back()->withErrors(['error_message' => 'Failed to update organization. Please try again.'])->withInput();
        }
    } catch (\Illuminate\Http\Client\RequestException $e) {
        Log::error('HTTP Request failed: ' . $e->getMessage());
        return back()->withErrors(['error_message' => 'HTTP Request failed: ' . $e->getMessage()])->withInput();
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return back()->withErrors(['error_message' => 'Something went wrong, try again! ' . $e->getMessage()])->withInput();
    }
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

    public function searchUsers(Request $request)
{
    // Log the incoming request data
    Log::info('Request Data:', $request->all());

    // Extract the email from the request
    $email = $request->input('email');
    Log::info('Searching for email', ['email' => $email]); // Corrected to ensure array context

    // Check if the email is null or empty
    if (empty($email)) {
        return response()->json([
            'success' => false,
            'message' => 'The email field is required.',
        ], 400);
    }

    // Prepare the payload for the API (simplified version)
    $payload = [
        'find' => $email // Directly send the email without wrapping it in 'body'
    ];
    Log::info('Payload being sent to API', ['payload' => $payload]); // Ensure payload is logged as array

    // Log the access token and API key for comparison
    Log::info('Access Token:', ['token' => session('access_token')]);
    Log::info('API Key:', ['api_key' => self::API_KEY]);

    // Call the external API using the full URL
    $response = Http::withHeaders([
            'Authorization' => session('access_token'), // Using the access token from session
            'x-api-key' => self::API_KEY, // Using the API key
        ])->post(self::API_URL . '/sso/list_user.json', $payload);

    // Log the entire response for debugging
    Log::info('API Response:', [
        'status' => $response->status(),
        'body' => $response->body(),
        'headers' => $response->headers(),
    ]);

    // Handle API response
    if ($response->successful()) {
        $data = $response->json();
        Log::info('API Parsed Response:', ['data' => $data]); // Log the parsed API response as array

        // Check if the response indicates success
        if (isset($data['success']) && $data['success']) {
            return response()->json([
                'success' => true,
                'data' => $data['data'], // List of users
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => $data['data'] ?? 'Unknown error.', // Error from the API
            ], 400);
        }
    } else {
        // Log the failure details
        Log::error('Failed API request. Status: ' . $response->status());

        return response()->json([
            'success' => false,
            'message' => 'Failed to connect to the API.',
        ], 500);
    }
}

public function sendAddMemberEmail(Request $request)
{
    // Validate input
    $request->validate([
        'organization_id' => 'required|string',  // organization_id validation
        'emails' => 'required|array',
        'emails.*' => 'email',  // validate each email
    ]);
    
    
    $organizationId = $request->input('organization_id');
    $emails = $request->input('emails');

    try {
        // Logging the emails for debugging
        Log::info('Received request to send emails:', ['emails' => $emails]);

        // Get member tokens for the emails
        $tokenResponse = $this->getMemberToken(new Request([
            'organization_id' => $organizationId,
            'user_emails' => $emails
        ]));

        // Check if the token response is successful
        if ($tokenResponse->status() !== 200) {
            return response()->json(['success' => false, 'message' => 'Failed to get member tokens.'], 500);
        }

        $responseData = json_decode($tokenResponse->getContent(), true);

        $tokens = $responseData['tokens'] ?? []; // Get tokens directly
        $failedEmails = $responseData['failed_emails'] ?? [];

        // Handle failed emails
        if (!empty($failedEmails)) {
            foreach ($failedEmails as $failedEmail) {
                Log::warning('Failed to generate token for email: ' . $failedEmail['email'] . ', Reason: ' . $failedEmail['reason']);
            }
        }

        // Proceed only if there are tokens available
        if (empty($tokens)) {
            Log::warning('No tokens generated for the provided emails.');
            return response()->json(['success' => false, 'message' => 'No tokens generated.'], 400);
        }

        // Process each token and send emails
        foreach ($tokens as $tokenData) {
            $email = $tokenData['email'];
            $token = $tokenData['token']; // Ensure this matches your actual API response

            Log::info('Attempting to send email to: ' . $email);

            // Send email with the token
            Log::info('Preparing to send verification email for: ' . $email);
            Mail::send('emails.verif-addmember', ['token' => $token], function($message) use ($email) {
                $message->to($email)
                    ->subject('Add Member Verification');
            });

            Log::info('Verification email sent to: ' . $email);
        }

        return response()->json(['success' => true, 'message' => 'Emails sent successfully!']);
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to send emails: ' . $e->getMessage()], 500);
    }
}






public function addMemberOrganization(Request $request)
{
    Log::info('Received add member organization request:', $request->all());

    $token = $request->input('token'); // Getting the token from the request

    if (empty($token)) {
        return response()->json(['success' => false, 'message' => 'Token is required.'], 400);
    }

    try {
        $response = Http::withHeaders([
            'x-api-key' => '5af97cb7eed7a5a4cff3ed91698d2ffb',
        ])->post(config('app.base_url') . '/sso/add_member_organization.json', [
            'token' => $token,
        ]);

        if ($response->successful()) {
            return response()->json(['success' => true, 'data' => $response->json()]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to add member organization.', 'error' => $response->body()], $response->status());
        }
    } catch (\Exception $e) {
        Log::error('Error adding member organization: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Internal server error: ' . $e->getMessage()], 500);
    }
}

public function getMemberToken(Request $request)
{
    Log::info('Received get member token request:', $request->all());

    // Validate incoming request
    $validator = Validator::make($request->all(), [
        'organization_id' => 'required|string',
        'user_emails' => 'required|array',
        'user_emails.*' => 'email',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed:', ['errors' => $validator->errors()]);
        return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }

    $organizationId = $request->input('organization_id');
    $userEmails = $request->input('user_emails');

    try {
        // Send request to get member tokens
        $response = Http::withHeaders([
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/get_member_token.json', [
            'organization_id' => $organizationId,
            'user_emails' => $userEmails,
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            Log::info('Successfully received member tokens:', ['data' => $responseData]);

            return response()->json($responseData);
        }

        // Handle unsuccessful response
        return response()->json([
            'success' => false,
            'message' => 'Failed to get member token.',
            'error' => $response->json(),
        ], $response->status());
    } catch (\Exception $e) {
        Log::error('Error getting member token', [
            'error' => 'Internal server error: ' . $e->getMessage()
        ]);
        return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
    }
}





}
