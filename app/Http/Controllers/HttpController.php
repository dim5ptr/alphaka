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
use App\Mail\VerifAddMember; // Add this line
use Illuminate\Support\Str;






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

     // Fungsi untuk redirect ke Google login page
  public function redirectToGoogle()
  {
      return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
{
    try {
        Log::info('Attempting to get user from Google...');
        $user = Socialite::driver('google')->user();

        $userData = [
            'google_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
        ];

        Log::info('User data received from Google:', $userData ?? []);

        // Try to log in with Google ID and email first
        Log::info('Attempting to log in with Google ID: ' . $userData['google_id']);
        $loginResponse = Http::withHeaders([
            'x-api-key' => self::API_KEY
        ])->post(self::API_URL . '/sso/login.json', [
            'username' => $userData['email'],
            'google_id' => $userData['google_id'],
        ]);

        $loginData = $loginResponse->json();
        Log::info('Login API response:', $loginData ?? []);

        // If login is successful
        if ($loginResponse->successful() && isset($loginData['data']['access_token'])) {
            session(['access_token' => $loginData['data']['access_token']]);
            Log::info('Access token saved in session: ' . $loginData['data']['access_token']);

            // Storing personal information in session
            if (isset($loginData['data']['personal_info'])) {
                $personalInfo = $loginData['data']['personal_info'];
                session([
                    'birthday' => $personalInfo['birthday'],
                    'full_name' => $personalInfo['full_name'],
                    'gender' => $personalInfo['gender'],
                    'phone' => $personalInfo['phone'],
                    'username' => $personalInfo['username'],
                    'email' => $userData['email'],
                    'profile_picture' => $personalInfo['profile_picture'] ?? $userData['avatar'],
                    'role_id' => $loginData['data']['role_id'] ?? null, // Store role_id in session
                ]);
                Log::info('Personal information saved in session:', $personalInfo ?? []);
            }

            Log::info('Login successful for user: ' . $userData['email']);
            // Redirect based on role_id
            $roleId = session('role_id');
            if ($roleId == 2) {
                return redirect()->route('showdashboardadm'); // Redirect to admin dashboard
            } elseif ($roleId == 1) {
                return redirect()->route('dashboard'); // Redirect to user dashboard
            } else {
                return back()->withErrors(['error' => 'Role not recognized.']);
            }
        }

        // If login fails, attempt registration
        Log::info('Login failed, attempting to register the user: ' . $userData['email']);
        $registerCheckResponse = Http::withHeaders([
            'x-api-key' => self::API_KEY
        ])->post(self::API_URL . '/sso/register-check.json', [
            'email' => $userData['email'],
        ]);

        $registerCheckData = $registerCheckResponse->json();
        Log::info('Register check API response:', $registerCheckData ?? []);

        // If user is not registered or Google ID needs updating
        if (!$registerCheckResponse->successful() || $registerCheckData['result'] == 'not_registered') {
            Log::info('User not registered, attempting registration for: ' . $userData['email']);

            // Registering the user
            $registerResponse = Http::withHeaders([
                'x-api-key' => self::API_KEY
            ])->post(self::API_URL . '/sso/register.json', [
                'email' => $userData['email'],
                'google_id' => $userData['google_id'],
                'name' => $userData['name'],
                'password' => bcrypt(Str::random(10)), // Generate a random password
            ]);

            $registerData = $registerResponse->json();
            Log::info('Registration API response:', $registerData ?? []);

            // Handle case where user needs to activate their account
            if ($registerResponse->successful() && isset($registerData['activation_key'])) {
                Log::info('Registration successful, activation required for: ' . $userData['email']);

                // Save the verification token in session
                session([
                    'verification_token' => $registerData['activation_key'],
                    'email' => $userData['email'],
                ]);

                // Sending verification email
                Log::info('Sending verification email to: ' . $userData['email']);
                Mail::send('emails.verification', ['token' => session('verification_token')], function($message) use ($userData) {
                    $message->to($userData['email']);
                    $message->subject('Email Activation');
                });
                Log::info('Verification email sent to: ' . $userData['email']);

                return redirect('/verify')->with('success_message', 'Please check your email to activate your account.');
            }

        // Handle case where Google ID is updated for an existing user
        } elseif ($registerCheckResponse->successful() && $registerCheckData['result'] == 1 && $registerCheckData['data'] == "Google ID successfully updated for existing user.") {
            Log::info('Google ID successfully updated for user, retrying login.');

            // Retry login after successful Google ID update
            $retryLoginResponse = Http::withHeaders([
                'x-api-key' => self::API_KEY
            ])->post(self::API_URL . '/sso/login.json', [
                'username' => $userData['email'],
                'google_id' => $userData['google_id'],
            ]);

            $retryLoginData = $retryLoginResponse->json();
            Log::info('Retry login API response:', $retryLoginData ?? []);

            // If login is successful after update
            if ($retryLoginResponse->successful() && isset($retryLoginData['data']['access_token'])) {
                session(['access_token' => $retryLoginData['data']['access_token']]);
                Log::info('Access token saved in session: ' . $retryLoginData['data']['access_token']);

                // Store personal information in session
                if (isset($retryLoginData['data']['personal_info'])) {
                    $personalInfo = $retryLoginData['data']['personal_info'];
                    session([
                        'birthday' => $personalInfo['birthday'],
                        'full_name' => $personalInfo['full_name'],
                        'gender' => $personalInfo['gender'],
                        'phone' => $personalInfo['phone'],
                        'username' => $personalInfo['username'],
                        'email' => $userData['email'],
                        'profile_picture' => $personalInfo['profile_picture'] ?? $userData['avatar'],
                        'role_id' => $retryLoginData['data']['role_id'] ?? null, // Store role_id in session
                    ]);
                    Log::info('Personal information saved in session:', $personalInfo ?? []);
                }

                // Redirect to dashboard after successful login
                Log::info('Login successful for user: ' . $userData['email']);
                // Redirect based on role_id
                $roleId = session('role_id');
                if ($roleId == 2) {
                    return redirect()->route('showdashboardadm'); // Redirect to admin dashboard
                } elseif ($roleId == 1) {
                    return redirect()->route('dashboard'); // Redirect to user dashboard
                } else {
                    return back()->withErrors(['error' => 'Role not recognized.']);
                }
            } else {
                Log::error('Retry login failed for user: ' . $userData['email']);
                return back()->withErrors(['error_message' => 'Login failed after Google ID update, please try again.']);
            }

        } else {
            Log::info('User is already registered, but login failed.');
            return back()->withErrors([
                'error_message' => 'Login failed, please try again.',
            ])->withInput();
        }

    } catch (\Exception $e) {
        Log::error('An error occurred during Google login/registration: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        return redirect('/login')->withErrors([
            'error_message' => 'Something went wrong, please try again.'
        ]);
    }
}

//   public function handleGoogleCallback()
//   {
//       try {
//           Log::info('Attempting to get user from Google...');
//           $user = Socialite::driver('google')->user();

//           $userData = [
//               'google_id' => $user->id,
//               'name' => $user->name,
//               'email' => $user->email,
//               'avatar' => $user->avatar,
//           ];

//           Log::info('User data received from Google:', $userData ?? []);

//           // Try to log in with Google ID and email first
//           Log::info('Attempting to log in with Google ID: ' . $userData['google_id']);
//           $loginResponse = Http::withHeaders([
//               'x-api-key' => self::API_KEY
//           ])->post(self::API_URL . '/sso/login.json', [
//               'username' => $userData['email'],
//               'google_id' => $userData['google_id'],
//           ]);

//           $loginData = $loginResponse->json();
//           Log::info('Login API response:', $loginData ?? []);

//           // If login is successful
//           if ($loginResponse->successful() && isset($loginData['data']['access_token'])) {
//               session(['access_token' => $loginData['data']['access_token']]);
//               Log::info('Access token saved in session: ' . $loginData['data']['access_token']);

//               // Storing personal information in session
//               if (isset($loginData['data']['personal_info'])) {
//                   $personalInfo = $loginData['data']['personal_info'];
//                   session([
//                       'birthday' => $personalInfo['birthday'],
//                       'full_name' => $personalInfo['full_name'],
//                       'gender' => $personalInfo['gender'],
//                       'phone' => $personalInfo['phone'],
//                       'username' => $personalInfo['username'],
//                       'email' => $userData['email'],
//                       'profile_picture' => $personalInfo['profile_picture'] ?? $userData['avatar'],
//                   ]);
//                   Log::info('Personal information saved in session:', $personalInfo ?? []);
//               }

//               Log::info('Login successful for user: ' . $userData['email']);
//               return redirect()->route('dashboard');
//           }

//           // If login fails, attempt registration
//           Log::info('Login failed, attempting to register the user: ' . $userData['email']);
//           $registerCheckResponse = Http::withHeaders([
//               'x-api-key' => self::API_KEY
//           ])->post(self::API_URL . '/sso/register-check.json', [
//               'email' => $userData['email'],
//           ]);

//           $registerCheckData = $registerCheckResponse->json();
//           Log::info('Register check API response:', $registerCheckData ?? []);

//           // If user is not registered or Google ID needs updating
//           if (!$registerCheckResponse->successful() || $registerCheckData['result'] == 'not_registered') {
//               Log::info('User not registered, attempting registration for: ' . $userData['email']);

//               // Registering the user
//               $registerResponse = Http::withHeaders([
//                   'x-api-key' => self::API_KEY
//               ])->post(self::API_URL . '/sso/register.json', [
//                   'email' => $userData['email'],
//                   'google_id' => $userData['google_id'],
//                   'name' => $userData['name'],
//                   'password' => bcrypt(Str::random(10)), // Generate a random password
//               ]);

//               $registerData = $registerResponse->json();
//               Log::info('Registration API response:', $registerData ?? []);

//               // Handle case where user needs to activate their account
//               if ($registerResponse->successful() && isset($registerData['activation_key'])) {
//                   Log::info('Registration successful, activation required for: ' . $userData['email']);

//                   // Save the verification token in session
//                   session([
//                       'verification_token' => $registerData['activation_key'],
//                       'email' => $userData['email'],
//                   ]);

//                   // Sending verification email
//                   Log::info('Sending verification email to: ' . $userData['email']);
//                   Mail::send('emails.verification', ['token' => session('verification_token')], function($message) use ($userData) {
//                       $message->to($userData['email']);
//                       $message->subject('Email Activation');
//                   });
//                   Log::info('Verification email sent to: ' . $userData['email']);

//                   return redirect('/verify')->with('success_message', 'Please check your email to activate your account.');
//               }

//           // Handle case where Google ID is updated for an existing user
//           } elseif ($registerCheckResponse->successful() && $registerCheckData['result'] == 1 && $registerCheckData['data'] == "Google ID successfully updated for existing user.") {
//             Log::info('Google ID successfully updated for user, retrying login.');

//             // Retry login after successful Google ID update
//             $retryLoginResponse = Http::withHeaders([
//                 'x-api-key' => self::API_KEY
//             ])->post(self::API_URL . '/sso/login.json', [
//                 'username' => $userData['email'],
//                 'google_id' => $userData['google_id'],
//             ]);

//             $retryLoginData = $retryLoginResponse->json();
//             Log::info('Retry login API response:', $retryLoginData ?? []);

//             // If login is successful after update
//             if ($retryLoginResponse->successful() && isset($retryLoginData['data']['access_token'])) {
//                 session(['access_token' => $retryLoginData['data']['access_token']]);
//                 Log::info('Access token saved in session: ' . $retryLoginData['data']['access_token']);

//                 // Store personal information in session
//                 if (isset($retryLoginData['data']['personal_info'])) {
//                     $personalInfo = $retryLoginData['data']['personal_info'];
//                     session([
//                         'birthday' => $personalInfo['birthday'],
//                         'full_name' => $personalInfo['full_name'],
//                         'gender' => $personalInfo['gender'],
//                         'phone' => $personalInfo['phone'],
//                         'username' => $personalInfo['username'],
//                         'email' => $userData['email'],
//                         'profile_picture' => $personalInfo['profile_picture'] ?? $userData['avatar'],
//                     ]);
//                     Log::info('Personal information saved in session:', $personalInfo ?? []);
//                 }

//                 // Redirect to dashboard after successful login
//                 Log::info('Login successful for user: ' . $userData['email']);
//                 return redirect()->route('dashboard');
//             } else {
//                 Log::error('Retry login failed for user: ' . $userData['email']);
//                 return back()->withErrors(['error_message' => 'Login failed after Google ID update, please try again.']);
//             }

//           } else {
//               Log::info('User is already registered, but login failed.');
//               return back()->withErrors([
//                   'error_message' => 'Login failed, please try again.',
//               ])->withInput();
//           }

//       } catch (\Exception $e) {
//           Log::error('An error occurred during Google login/registration: ' . $e->getMessage());
//           Log::error('Stack trace: ' . $e->getTraceAsString());

//           return redirect('/login')->withErrors([
//               'error_message' => 'Something went wrong, please try again.'
//           ]);
//       }
//   }

//   public function handleGoogleCallback()
// {
//     try {
//         Log::info('Attempting to get user from Google...');
//         $user = Socialite::driver('google')->user();

//         $userData = [
//             'google_id' => $user->id,
//             'name' => $user->name,
//             'email' => $user->email,
//             'avatar' => $user->avatar,
//         ];

//         Log::info('User data received from Google:', $userData ?? []);

//         // Try to log in with Google ID and email first
//         Log::info('Attempting to log in with Google ID: ' . $userData['google_id']);
//         $loginResponse = Http::withHeaders([
//             'x-api-key' => self::API_KEY
//         ])->post(self::API_URL . '/sso/login.json', [
//             'username' => $userData['email'],
//             'google_id' => $userData['google_id'],
//         ]);

//         $loginData = $loginResponse->json();
//         Log::info('Login API response:', $loginData ?? []);

//         // If login is successful
//         if ($loginResponse->successful() && isset($loginData['data']['access_token'])) {
//             session(['access_token' => $loginData['data']['access_token']]);
//             Log::info('Access token saved in session: ' . $loginData['data']['access_token']);

//             // Storing personal information in session
//             if (isset($loginData['data']['personal_info'])) {
//                 $personalInfo = $loginData['data']['personal_info'];
//                 session([
//                     'birthday' => $personalInfo['birthday'],
//                     'full_name' => $personalInfo['full_name'],
//                     'gender' => $personalInfo['gender'],
//                     'phone' => $personalInfo['phone'],
//                     'username' => $personalInfo['username'],
//                     'email' => $userData['email'],
//                     'profile_picture' => $personalInfo['profile_picture'] ?? $userData['avatar'],
//                 ]);
//                 Log::info('Personal information saved in session:', $personalInfo ?? []);
//             }

//             Log::info('Login successful for user: ' . $userData['email']);
//             return redirect()->route('dashboard');
//         }

//         // If login fails, attempt registration
//         Log::info('Login failed, attempting to register the user: ' . $userData['email']);
//         $registerCheckResponse = Http::withHeaders([
//             'x-api-key' => self::API_KEY
//         ])->post(self::API_URL . '/sso/register-check.json', [
//             'email' => $userData['email'],
//         ]);

//         $registerCheckData = $registerCheckResponse->json();
//         Log::info('Register check API response:', $registerCheckData ?? []);

//         // If user is not registered or Google ID needs updating
//         if (!$registerCheckResponse->successful() || $registerCheckData['result'] == 'not_registered') {
//             Log::info('User not registered, attempting registration for: ' . $userData['email']);

//             // Registering the user
//             $registerResponse = Http::withHeaders([
//                 'x-api-key' => self::API_KEY
//             ])->post(self::API_URL . '/sso/register.json', [
//                 'email' => $userData['email'],
//                 'google_id' => $userData['google_id'],
//                 'name' => $userData['name'],
//                 'password' => bcrypt(Str::random(10)), // Generate a random password
//             ]);

//             $registerData = $registerResponse->json();
//             Log::info('Registration API response:', $registerData ?? []);

//             // If registration successful and Google ID is updated
//             if ($registerResponse->successful() && $registerData['result'] == 1 && $registerData['data'] == "Google ID successfully updated for existing user.") {
//                 Log::info('Google ID successfully updated for user, retrying login.');

//                 // Retry login after successful Google ID update
//                 $retryLoginResponse = Http::withHeaders([
//                     'x-api-key' => self::API_KEY
//                 ])->post(self::API_URL . '/sso/login.json', [
//                     'username' => $userData['email'],
//                     'google_id' => $userData['google_id'],
//                 ]);

//                 $retryLoginData = $retryLoginResponse->json();

//                 // If login is successful after update
//                 if ($retryLoginResponse->successful() && isset($retryLoginData['data']['access_token'])) {
//                     session(['access_token' => $retryLoginData['data']['access_token']]);
//                     Log::info('Access token saved in session: ' . $retryLoginData['data']['access_token']);

//                     // Store personal information in session
//                     if (isset($retryLoginData['data']['personal_info'])) {
//                         $personalInfo = $retryLoginData['data']['personal_info'];
//                         session([
//                             'birthday' => $personalInfo['birthday'],
//                             'full_name' => $personalInfo['full_name'],
//                             'gender' => $personalInfo['gender'],
//                             'phone' => $personalInfo['phone'],
//                             'username' => $personalInfo['username'],
//                             'email' => $userData['email'],
//                             'profile_picture' => $personalInfo['profile_picture'] ?? $userData['avatar'],
//                         ]);
//                         Log::info('Personal information saved in session:', $personalInfo ?? []);
//                     }

//                     Log::info('Login successful for user: ' . $userData['email']);
//                     return redirect()->route('dashboard');
//                 }
//             } else {
//                 Log::warning('Registration failed for user: ' . $userData['email'], [
//                     'api_response' => $registerData ?? [],
//                     'status' => $registerResponse->status(),
//                 ]);

//                 return back()->withErrors([
//                     'error_message' => $registerData['data'] ?? 'Registration failed, please try again.',
//                 ])->withInput();
//             }
//         } else {
//             Log::info('User is already registered, but login failed.');
//             return back()->withErrors([
//                 'error_message' => 'Login failed, please try again.',
//             ])->withInput();
//         }

//     } catch (\Exception $e) {
//         Log::error('An error occurred during Google login/registration: ' . $e->getMessage());
//         Log::error('Stack trace: ' . $e->getTraceAsString());

//         return redirect('/login')->withErrors([
//             'error_message' => 'Something went wrong, please try again.'
//         ]);
//     }
// }



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
                    'profile_picture' => $personalInfo['profile_picture'] ?? null,
                    'role_id' => $responseData['data']['role_id'] ?? null, // Menyimpan role_id ke session
                ]);

                Log::info('Session data stored successfully.', [
                    'session_data' => session()->all()
                ]);
            }

            // Redirect based on role_id
            $roleId = session('role_id'); // Mengambil role_id dari session
            if ($roleId == 2) {
                return redirect()->route('showdashboardadm'); // Redirect to admin dashboard
            } elseif ($roleId == 1) {
                return redirect()->route('dashboard'); // Redirect to user dashboard
            } else {
                return back()->withErrors([
                    'error' => 'Role not recognized.',
                ])->withInput();
            }
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


    // public function login(Request $request)
    // {
    //     try {
    //         $response = Http::withHeaders([
    //             'x-api-key' => self::API_KEY
    //         ])->post(self::API_URL . '/sso/login.json', [
    //             'username' => $request->email,
    //             'password' => $request->password,
    //         ]);
    //         Log::info('API request sent.', [
    //             'url' => self::API_URL . '/sso/login.json',
    //             'response_status' => $response->status(),
    //             'response_body' => $response->body()
    //         ]);
    //         $responseData = $response->json();

    //         if ($response->successful() && isset($responseData['data']['access_token'])) {

    //             // Simpan access token ke session
    //             session(['access_token' => $responseData['data']['access_token']]);

    //             // Jika personal_info tersedia dalam respons, simpan data tersebut ke session juga
    //             if (isset($responseData['data']['personal_info'])) {
    //                 $personalInfo = $responseData['data']['personal_info'];
    //                 session([
    //                     'birthday' => $personalInfo['birthday'],
    //                     'full_name' => $personalInfo['full_name'],
    //                     'gender' => $personalInfo['gender'],
    //                     'phone' => $personalInfo['phone'],
    //                     'username' => $personalInfo['username'],
    //                     'email' => $request->email,
    //                     'profile_picture' => $personalInfo['profile_picture'] ?? null,// Simpan email ke session
    //                 ]);

    //                 // Simpan URL gambar profil ke session jika tersedia
    //                 if (isset($personalInfo['profile_picture'])) {
    //                     session(['profile_picture' => $personalInfo['profile_picture']]);
    //                 }
    //                 Log::info('Session data stored successfully.', [
    //                     'session_data' => session()->all()
    //                 ]);
    //             }

    //             return redirect()->route('dashboard');
    //         } elseif (isset($responseData['data']) && $responseData['result'] === 2) {
    //             return back()->withErrors([
    //                 'error' => $responseData['data'],
    //             ])->withInput();
    //         } elseif (isset($responseData['data']) && $responseData['result'] === 3) {
    //             return back()->withErrors([
    //                 'error' => $responseData['data'],
    //             ])->withInput();
    //         } elseif (isset($responseData['data']) && $responseData['result'] === 4) {
    //             return back()->withErrors([
    //                 'error' => $responseData['data'],
    //             ])->withInput();
    //         }

    //         return back()->withErrors([
    //             'email' => 'The provided credentials do not match our records.',
    //         ])->withInput();
    //     } catch (\Exception $e) {
    //         // Tangani kesalahan jika terjadi kesalahan dalam melakukan permintaan HTTP
    //         return back()->withErrors([
    //             'error' => 'Something went wrong. Please try again later.'
    //         ])->withInput();
    //     }
    // }


    public function index()
    {

        return view('welcome');
    }

    public function showdashboardadm()
    {
        // Log current session data when accessing the dashboard
        Log::info('Accessing dashboard:', [
            'username' => session('username'),
            'email' => session('email'),
            'admin_user_id' => session('admin_user_id'), // Assuming you store the admin's user ID in the session
        ]);

        // Ensure the email is available in the session
        $email = session('email');
        if (!$email) {
            return back()->withErrors('User  email not found in session.');
        }

        // Prepare the API request
        try {
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/product/count_all_entities.json', [
                'email' => $email,
            ]);

            // Log the API response for debugging
            Log::info('Count Entities API Response:', ['response' => $response->json()]);

            // Check if API response is successful
            if ($response->successful()) {
                $data = $response->json();

                // Extract counts from the data
                $organizationCount = $data['data']['verified_organizations'] ?? 0;
                $userCount = $data['data']['user_count'] ?? 0;
                $productCount = $data['data']['enabled_products'] ?? 0;
                $transactionCount = $data['data']['cash_receipt_transactions'] ?? 0;

                // Pass the counts to the view
                return view('admin.dashboardadmin', compact('organizationCount', 'userCount', 'productCount', 'transactionCount'));
            } else {
                return back()->withErrors('Failed to fetch dashboard data. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard data:', ['message' => $e->getMessage()]);
            return back()->withErrors('An error occurred while fetching dashboard data.');
        }
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
            'x-api-key' => self::API_KEY,
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
                        'member_count' => $org['member_count'] ?? 0, // Check if member_count exists, default to 0
                    ];

                    // Log the prepared organization data with member_count
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


    public function showmoredetails(Request $request, $organization_name)
    {
        Log::info('Attempting to show more details for organization: ' . $organization_name);

        try {
            // Get the email from the request
            $email = $request->input('email');
            Log::info('Email: ' . $email); // Log email value

            // Get organization details using the showvieworganization function
            $organization = $this->showvieworganization($organization_name)->getData()['organization'];

            // Prepare the payload for the API request
            $payload = ['email' => $email];

            // Send the email to the external API to get user details
            $response = Http::withHeaders([
                'x-api-key' => self::API_KEY, // Ensure the API key is correct
            ])->post(self::API_URL . '/sso/get_user_details_by_email.json', $payload); // Verify the endpoint URL

            Log::info('API Request URL: ' . self::API_URL . '/sso/get_user_details_by_email.json');
            Log::info('API Payload: ' . json_encode($payload));

            // Log the status and body of the API response for further investigation
            Log::info('API Response Status: ' . $response->status());
            Log::info('API Response Body: ' . $response->body());

            // Check if the API response is successful
            if ($response->successful()) {
                $userData = $response->json();

                // Handle null values in the response by replacing them with 'N/A'
                $fullname = $userData['full_name'] ?? 'N/A';
                $dateofbirth = $userData['birthday'] ?? 'N/A';
                $gender = $userData['gender'] ?? 'N/A';
                $email = $userData['email'] ?? 'N/A';
                $phone = $userData['phone'] ?? 'N/A';

                // Handle roles, check for null values and empty array
                if (isset($userData['roles']) && is_array($userData['roles']) && !empty($userData['roles'])) {
                    // Filter out null roles and implode the array
                    $roles = array_filter($userData['roles'], function ($role) {
                        return $role !== null; // Remove null values
                    });
                    $user_role = !empty($roles) ? implode(', ', $roles) : 'N/A';
                } else {
                    $user_role = 'N/A'; // Set user_role to 'N/A' if roles array is empty or null
                }

                // Store user data in the session
                session([
                    'fullname' => $fullname,
                    'dateofbirth' => $dateofbirth,
                    'gender' => $gender,
                    'email' => $email,
                    'phone' => $phone,
                    'user_role' => $user_role,
                ]);

                Log::info('User details retrieved successfully for: ' . $email);

                // Return the view with the organization and organization name
                return view('moredetails', compact('organization', 'organization_name'));
            } else {
                // Handle non-200 responses (e.g., 404 or 400)
                Log::error('Failed to get user details from API. Status: ' . $response->status());
                return back()->with('error', 'Failed to get user details from API: ' . $response->body());
            }
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

    public function gravatar(Request $request)
{
    Log::info('Starting Gravatar retrieval process.');

    try {
        // Get user's email from session
        $userEmail = session('email');

        // Check if the email exists
        if (empty($userEmail)) {
            Log::error('No email found in session.');
            return redirect()->back()->with('error', 'No email found. Please log in again.');
        }

        // Send request to the external API to get Gravatar
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
            'Content-Type' => 'application/json',
        ])->post(self::API_URL . '/sso/gravatar.json', [
            'email' => $userEmail,
        ]);

        Log::info('API response received.', ['status' => $response->status()]);

        if ($response->successful()) {
            // Parse response data
            $data = $response->json();
            Log::info('Response data:', ['data' => $data]);

            // Check if there's a "No profile picture" message
            if (isset($data['data']['data']) && strpos($data['data']['data'], 'No profile picture, using Gravatar') !== false) {
                Log::info('No profile picture found, redirecting to Gravatar upload page.');
                return response()->json([
                    'message' => 'No profile picture, using Gravatar',
                    'redirect' => 'https://gravatar.com'
                ]);
            }

            // Get the Gravatar URL from the response
            $gravatarUrl = $data['data']['gravatar_url'] ?? null;

            if (!$gravatarUrl) {
                Log::error('Gravatar URL not found in response data.');
                return response()->json([
                    'error' => 'Gravatar URL not found'
                ], 400);
            }

            Log::info('Gravatar URL retrieved successfully.', ['gravatarUrl' => $gravatarUrl]);

            // Store the Gravatar URL in the session or database if necessary
            session(['gravatar_url' => $gravatarUrl]);

            return response()->json([
                'gravatarUrl' => $gravatarUrl,
                'message' => 'Gravatar retrieved successfully.'
            ]);
        } else {
            $errorMessage = $response->json()['message'] ?? 'An error occurred while retrieving Gravatar.';
            Log::error('API error:', ['message' => $errorMessage]);
            return response()->json(['error' => $errorMessage], 400);
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred during API request:', ['exception' => $e->getMessage()]);
        return response()->json(['error' => 'An error occurred during the Gravatar retrieval process.'], 500);
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

public function showActivityUser()
{
    return view('user.activity');
}

public function activityUser(Request $request)
{
    // Validasi input dari request
    $request->validate([
        'access_token' => 'required|string', // Validasi token akses
    ]);

    // Ambil token akses dari input
    $accessToken = $request->input('access_token');
    Log::info('Access token retrieved from input:', ['access_token' => $accessToken]);

    // Cek keberadaan token akses
    if (!$accessToken) {
        Log::warning('Unauthorized access attempt: Access token not found.');
        return redirect()->back()->with('error', 'Unauthorized: Access token not found.');
    }

    // Mengirim permintaan POST ke API
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => $accessToken,
    ])->post(self::API_URL . '/sso/user_activity.json');

    // Menyimpan log respons API
    Log::info('API response received:', ['response' => $response->json()]);

    // Mengambil data dari respons API
    $responseData = $response->json();
    Log::info('Complete API response:', ['response' => $responseData]);

    // Ambil data sesi dari respons API jika ada
    $sessionData = [
        'created_date' => null,
        'last_update' => null,
        'user_id' => null,
    ];

    if (isset($responseData['response']) && isset($responseData['response']['data']) && $responseData['response']['success']) {
        // Ambil data sesi dari respons API
        $sessionData = $responseData['response']['data'];
        Log::info('Session data to view:', ['sessions' => $sessionData]);

        // Simpan data sesi ke dalam session
        session([
            'created_date' => $sessionData['created_date'],
            'last_update' => $sessionData['last_update'],
            'user_id' => $sessionData['user_id'],
        ]);

        Log::info('Session data saved:', ['session_data' => session()->all()]);
    } else {
        Log::error('No valid session data in API response, using default values:', ['response' => $responseData]);
    }

    // Kirim data ke view
    return view('security', [
        'sessions' => $sessionData,
    ]);
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

    public function edituseradm(Request $request)
    {
        // Validate input fields including user_id
        $request->validate([
            'user_id' => 'required|integer', // User ID of the user being edited
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/',
            'dateofbirth' => 'required|date|before:today',
            'gender' => 'required|in:0,1',
            'role_id' => 'required|integer',
        ]);

        // Prepare data for API
        $data = [
            'user_id' => $request->user_id,
            'full_name' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'first_name' => strtok($request->fullname, ' '),
            'last_name' => substr(strstr($request->fullname, " "), 1) ?: '',
            'birthday' => $request->dateofbirth,
            'gender' => $request->gender,
            'role_id' => $request->role_id,
        ];

        // Log current session data before the API call
        Log::info('Session before edit:', [
            'username' => session('username'),
            'email' => session('email'),
            'admin_user_id' => session('admin_user_id'), // Assuming you store the admin's user ID in the session
        ]);

        // Send API request
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY
        ])->post(self::API_URL . '/update_user_data.json', $data);

        // Handle response
        if ($response->successful()) {
            // Check if the edited user is the logged-in admin
            if ($request->user_id == session('admin_user_id')) { // Assuming you store the admin's user ID in the session
                // Update session data for the logged-in admin if their own data was edited
                session(['username' => $request->username, 'email' => $request->email]);
            }

            // Log session data after the edit
            Log::info('Session after edit:', [
                'username' => session('username'),
                'email' => session('email'),
            ]);

            // Redirect to moredetailsadm instead of showedituseradm
            return redirect()->route('showmoredetailsadm', ['email' => $request->email])->with('success', 'User  data updated successfully.');
        } else {
            return redirect()->route('showedituseradm')->with('error', 'Failed to update user data. Please try again.');
        }
    }

    public function showUserDetails(Request $request)
    {
        // Validate the email being requested
        $request->validate([
            'email' => 'required|email',
        ]);

        // Log current session data before the API call
        Log::info('Session before retrieving user details:', [
            'username' => session('username'),
            'email' => session('email'),
        ]);

        // Send API request to get user details
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY
        ])->post(self::API_URL . '/get_user_details_by_email.json', [
            'email' => $request->email,
        ]);

        // Handle response
        if ($response->successful()) {
            $userDetails = $response->json()['data'];

            // Log the retrieved user details
            Log::info('User  details retrieved:', $userDetails);

            // Render the view with user details without modifying the session
            return view('user.details', compact('userDetails'));
        } else {
            return redirect()->back()->with('error', 'Failed to retrieve user details. Please try again.');
        }
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

        // Store the first token in the session (you can decide how you want to handle this)
        $firstToken = $tokens[0]['token']; // Ensure this matches your actual API response structure
        session(['token' => $firstToken]);

        // Log for ensuring the token is stored
        Log::info('Token stored in session:', ['token' => $firstToken]);

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



public function confirmJoin(Request $request)
{
    $token = $request->input('token'); // Getting the token from the request

    if (empty($token)) {
        return response()->json(['success' => false, 'message' => 'Token is required.'], 400);
    }

    // Redirecting to the addMemberOrganization route with the token
    return redirect()->route('addMemberView', ['token' => $token]);
}

public function addMemberOrganization(Request $request)
{
    Log::info('Received add member organization request:', $request->all());

    // Retrieve the token from the session
    $token = session('token');
    Log::info('Token in session:', ['token' => $token]);

    if (empty($token)) {
        // Redirect back with an error message if the token is not available
        return back()->withErrors(['message' => 'Token is required.']);
    }

    try {
        // Log the token to ensure it is correct
        Log::info('Adding member organization for token:', ['token' => $token]);

        // Step 1: Proceed with the API call to add the member organization
        $response = Http::withHeaders([
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/add_member_organization.json', [
            'token' => $token,
        ]);

        // Log the full response
        Log::info('API Response:', $response->json());

        if ($response->successful()) {
            // Get success message from the response if available
            $successMessage = $response->json()['data'] ?? 'Member added successfully.';
            return back()->with('success', $successMessage);
        } else {
            // Extract the error message from the API response
            $errorMessage = $response->json()['data'] ?? 'Failed to add member organization.';
            return back()->withErrors(['message' => $errorMessage]);
        }
    } catch (\Exception $e) {
        Log::error('Error adding member organization: ' . $e->getMessage());
        // Redirect back with an error message in case of an exception
        return back()->withErrors(['message' => 'Internal server error: ' . $e->getMessage()]);
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


public function showuserdata(Request $request)
{
    try {
        // Make the GET request to the external API
        $response = Http::withHeaders([
                    'Authorization' => session('access_token'),
                    'x-api-key' => self::API_KEY, // Ensure API_KEY is set in your .env file
                ])->get(self::API_URL .  '/sso/get_user_data.json');

        if ($response->successful()) {
            return view('admin.datapengguna', [
                'users' => $response->json()['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve user data.',
        ], $response->status());

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}
// public function showuserdata()
// {
//     $response = Http::withHeaders([
//         'Authorization' => session('access_token'),
//         'x-api-key' => self::API_KEY, // Ensure API_KEY is set in your .env file
//     ])->get(self::API_URL .  '/sso/get_user_data.json');

//     $users = $response->json();

//     return view ('admin.datapengguna');
// }

public function showuserrole()
{
    // Make API request to get role data
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => session('access_token'),
    ])->get(self::API_URL . '/sso/get_role_data.json');

    // Check if the request was successful
    if ($response->successful()) {
        // Parse the JSON response and get roles data
        $roles = $response->json('roles'); // Ensure the response contains a 'roles' key
        return view('admin.userrole', compact('roles'));
    } else {
        // Handle API error
        return back()->withErrors('Failed to fetch roles data.');
    }
}
public function showcreaterole()
    {
        return view('admin.createrole'); // Return the view for creating a role
    }
public function createrole(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'role' => 'required|string|max:255',
    ]);

    // Prepare API request to create the role
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY, // Set your API key in the .env file
        'Authorization' => session('access_token'),
    ])->post(self::API_URL . '/sso/create_role.json', [
        'role' => $request->input('role'),
    ]);

    // Check the response status
    if ($response->successful()) {
        return redirect()->route('showuserrole')->with('success', 'Role created successfully!');
    }

    return redirect()->route('showcreaterole')->with('error', 'Failed to create role.');
}
public function showupdaterole($idrole)
{
    // Fetch the role data from the API
    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => session('access_token'),
    ])->get(self::API_URL . '/sso/get_role_by_id.json', ['id' => $idrole]);

    // Check if the response was successful
    if ($response->successful()) {
        $role = $response->json();

        // Log the response for debugging
        Log::info('Role data:', $role);

        return view('admin.editrole', ['role' => $role]);
    }

    return redirect()->route('showuserrole')->with('error', 'Failed to fetch role data.');
}

public function updateRole(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'id' => 'required|integer',
        'role_name' => 'required|string|max:255',
    ]);

    // Prepare data for API
    $data = [
        'id' => $request->id,
        'role_name' => $request->role_name,
    ];

    // Log current session data before the API call
    Log::info('Session before role update:', [
        'role_id' => $request->id,
        'role_name' => $request->role_name,
    ]);

    // Send API request
    $response = Http::withHeaders([
        'Authorization' => session('access_token'),
        'x-api-key' => self::API_KEY,
    ])->post(self::API_URL . '/sso/update_role.json', $data);

    // Handle response
    if ($response->successful()) {
        return redirect()->route('showuserrole')->with('success', 'Role updated successfully!');
    }

    return redirect()->route('showupdaterole', ['idrole' => $request->id])->with('error', 'Failed to update role.');
}

public function showmoredetailsadm(Request $request)
{
    Log::info('Attempting to show more details.');

    // Validate email input to ensure it's provided and is a valid email format
    $request->validate([
        'email' => 'required|email'
    ]);

    $email = $request->input('email');
    Log::info('Email: ' . $email);

    try {
        // Prepare the payload for the API request
        $payload = ['email' => $email];

        // Send the email to the external API to get user details
        $response = Http::withHeaders([
            'x-api-key' => self::API_KEY // Ensure API key is accurate
        ])->post(self::API_URL  . '/sso/get_user_details_by_email.json', $payload);

        Log::info('API Request URL: ' . env('API_URL') . '/sso/get_user_details_by_email.json');
        Log::info('API Payload: ' . json_encode($payload));

        // Log the status and body of the API response
        Log::info('API Response Status: ' . $response->status());
        Log::info('API Response Body: ' . $response->body());

        // Check if the API response is successful
        if ($response->successful()) {
            $userData = $response->json();

            // Extract user details, handling null values with 'N/A'
            $fullname = $userData['full_name'] ?? 'N/A';
            $dateofbirth = $userData['birthday'] ?? 'N/A';
            $gender = isset($userData['gender']) && $userData['gender'] === 0 ? 'Female' : 'Male';
            $phone = $userData['phone'] ?? 'N/A';
            $emails = $userData['email'] ?? 'N/A';
            $roles = !empty($userData['roles']) ? implode(', ', array_filter($userData['roles'])) : 'N/A';
            $userId = $userData['user_id'] ?? null;
            $user_name = $userData['username'] ?? 'N/A'; // Get username from response

            // Store the data in session
            session([
                'user_id' => $userId,
                'fullname' => $fullname,
                'dateofbirth' => $dateofbirth,
                'gender' => $gender,
                'emails' => $emails,
                'phone' => $phone,
                'user_role' => $roles,
                'user_name' => $user_name,
            ]);

            Log::info('User details retrieved successfully for: ' . $email);

            return view('admin.moredetails');
        } else {
            // Handle non-200 responses
            Log::error('Failed to get user details from API. Status: ' . $response->status());
            return back()->with('error', 'Failed to get user details from API: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while showing more details: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

public function showProducts(Request $request)
{
    try {
        // Make the GET request to the external API using the constants
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_show.json');

        // Log the API response for debugging
        Log::info('API Response: ' . $response->body());

        // Check if the response was successful
        if ($response->successful()) {
            $products = $response->json()['data'] ?? []; // Default to an empty array if 'data' is not set
            return view('admin.products', ['products' => $products]);
        }

        Log::error('API request failed: ' . $response->body());
        return response()->json(['success' => false, 'message' => 'Failed to retrieve product data.'], $response->status());

    } catch (\Exception $e) {
        Log::error('Error retrieving data: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}

public function showProductsFolder(Request $request)
{
    try {
        // Make the GET request to the external API using the constants
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_folder_show.json');

        // Log the API response for debugging
        Log::info('API Response: ' . $response->body());

        // Check if the response was successful
        if ($response->successful()) {
            $productF = $response->json()['data'] ?? []; // Default to an empty array if 'data' is not set
            return view('admin.productFolder', ['products' => $productF]);
        }

        Log::error('API request failed: ' . $response->body());
        return response()->json(['success' => false, 'message' => 'Failed to retrieve product data.'], $response->status());

    } catch (\Exception $e) {
        Log::error('Error retrieving data: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}

public function showProductsFeatures(Request $request)
{
    try {
        // Make the GET request to the external API using the constants
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_features_show.json');

        // Log the API response for debugging
        Log::info('API Response: ' . $response->body());

        // Check if the response was successful
        if ($response->successful()) {
            $productFr = $response->json()['data'] ?? []; // Default to an empty array if 'data' is not set
            return view('admin.productFeature', ['products' => $productFr]);
        }

        Log::error('API request failed: ' . $response->body());
        return response()->json(['success' => false, 'message' => 'Failed to retrieve product data.'], $response->status());

    } catch (\Exception $e) {
        Log::error('Error retrieving data: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}

public function showProductsRelease(Request $request)
{
    try {
        // Make the GET request to the external API using the constants
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_release_show.json');

        // Log the API response for debugging
        Log::info('API Response: ' . $response->body());

        // Check if the response was successful
        if ($response->successful()) {
            $productR = $response->json()['data'] ?? []; // Default to an empty array if 'data' is not set
            return view('admin.productRelease', ['products' => $productR]);
        }

        Log::error('API request failed: ' . $response->body());
        return response()->json(['success' => false, 'message' => 'Failed to retrieve product data.'], $response->status());

    } catch (\Exception $e) {
        Log::error('Error retrieving data: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}


public function showTransaction(Request $request)
{
    try {
        // Make the GET request to the external API
        $response = Http::withHeaders([
                    'Authorization' => session('access_token'),
                    'x-api-key' => self::API_KEY, // Ensure API_KEY is set in your .env file
                ])->get(self::API_URL .  '/finance/receipt_show.json');

        if ($response->successful()) {
            return view('admin.datatransaksi', [
                'transactions' => $response->json()['data'] // Mengubah 'users' menjadi 'transactions'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve transaction data.',
        ], $response->status());

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}

}
