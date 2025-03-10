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
use Illuminate\Validation\ValidationException;







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
    try {
        // Prepare the API request to get the product data
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_show.json');

        // Log the API response for debugging
        Log::info('Product API Response:', ['response' => $response->json()]);

        // Check if the API response is successful
        if ($response->successful()) {
            $products = $response->json()['data']; // Extract the products array
        } else {
            // Handle the error response
            Log::error('Failed to fetch products:', ['response' => $response->json()]);
            $products = []; // Default to an empty array if fetching fails
        }
    } catch (\Exception $e) {
        Log::error('Error fetching products:', ['message' => $e->getMessage()]);
        $products = []; // Default to an empty array in case of an exception
    }

    // Pass the products data and current page name to the view
    return view('welcome', [
        'products' => $products,
        'currentPage' => 'Dashboard' // Set the current page name
    ]);
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
            return view('organization', ['organizations' => $organizations, 'currentPage' => 'Organization']);
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
        $currentPage = 'Add Organization'; // Define the current page variable
        return view('addorganization', compact('currentPage')); // Pass the variable to the view
    }

public function addOrganization(Request $request)
{
    $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session

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

            $this->saveInboxMessage($userId, 'success', 'Organization created successfully',  $arg_request);
            return redirect('/organization')->with('success_message', 'Organization created successfully.');
        } else {
            // Log error and show error message
            Log::error('Failed to add organization. Response: ' . $response->body());
            $this->saveInboxMessage($userId, 'error', 'Failed to add organization. Please try again.',  $arg_request);
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

    $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session

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
                $this->saveInboxMessage($userId, 'success', 'Member added successfully',  $arg_request);
                return redirect()->route('showvieworganization', $id)
                    ->with('success', 'Member added successfully.');
            } else {
                $this->saveInboxMessage($userId, 'error', 'Failed to add member. Please try again',  $arg_request);
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

    $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session


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
            $this->saveInboxMessage($userId, 'success', 'Your organization has been activated successfully',  $arg_request);
            return redirect('/organization')->with('success_message', 'Your organization has been activated successfully.');
        } else {
            // Log error and show error message
            Log::error('Failed to activate organization. Response: ' . $response->body());
            $this->saveInboxMessage($userId, 'error', 'Failed to activate your organization. Please try again',  $arg_request);
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
    $currentPage = 'Organization'; // Define the current page variable

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

                    return view('vieworganization', compact('organization', 'currentPage' ));
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


    // public function showmoredetails(Request $request, $organization_name)
    // {
    //     Log::info('Attempting to show more details for organization: ' . $organization_name);

    //     try {
    //         // Get the email from the request
    //         $email = $request->input('email');
    //         Log::info('Email: ' . $email); // Log email value

    //         // Get organization details using the showvieworganization function
    //         $organization = $this->showvieworganization($organization_name)->getData()['organization'];

    //         // Prepare the payload for the API request
    //         $payload = ['email' => $email];

    //         // Send the email to the external API to get user details
    //         $response = Http::withHeaders([
    //             'x-api-key' => self::API_KEY, // Ensure the API key is correct
    //         ])->post(self::API_URL . '/sso/get_user_details_by_email.json', $payload); // Verify the endpoint URL

    //         Log::info('API Request URL: ' . self::API_URL . '/sso/get_user_details_by_email.json');
    //         Log::info('API Payload: ' . json_encode($payload));

    //         // Log the status and body of the API response for further investigation
    //         Log::info('API Response Status: ' . $response->status());
    //         Log::info('API Response Body: ' . $response->body());

    //         // Check if the API response is successful
    //         if ($response->successful()) {
    //             $userData = $response->json();

    //             // Handle null values in the response by replacing them with 'N/A'
    //             $fullname = $userData['full_name'] ?? 'N/A';
    //             $dateofbirth = $userData['birthday'] ?? 'N/A';
    //             $gender = $userData['gender'] ?? 'N/A';
    //             $email = $userData['email'] ?? 'N/A';
    //             $phone = $userData['phone'] ?? 'N/A';

    //             // Handle roles, check for null values and empty array
    //             if (isset($userData['roles']) && is_array($userData['roles']) && !empty($userData['roles'])) {
    //                 // Filter out null roles and implode the array
    //                 $roles = array_filter($userData['roles'], function ($role) {
    //                     return $role !== null; // Remove null values
    //                 });
    //                 $user_role = !empty($roles) ? implode(', ', $roles) : 'N/A';
    //             } else {
    //                 $user_role = 'N/A'; // Set user_role to 'N/A' if roles array is empty or null
    //             }

    //             // Store user data in the session
    //             session([
    //                 'fullname' => $fullname,
    //                 'dateofbirth' => $dateofbirth,
    //                 'gender' => $gender,
    //                 'email' => $email,
    //                 'phone' => $phone,
    //                 'user_role' => $user_role,
    //             ]);

    //             Log::info('User details retrieved successfully for: ' . $email);

    //             // Return the view with the organization and organization name
    //             return view('moredetails', compact('organization', 'organization_name'));
    //         } else {
    //             // Handle non-200 responses (e.g., 404 or 400)
    //             Log::error('Failed to get user details from API. Status: ' . $response->status());
    //             return back()->with('error', 'Failed to get user details from API: ' . $response->body());
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Exception occurred while showing more details: ' . $e->getMessage());
    //         return back()->with('error', $e->getMessage());
    //     }
    // }
    public function showmoredetails(Request $request, $organization_name)
    {
        Log::info('Attempting to show more details for organization: ' . $organization_name);
        $currentPage = 'Organization';
        try {
            // Get the email from the request
            $email = $request->input('email');
            Log::info('Email from request: ' . $email); // Log email value from request

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
                $userData = $response->json()['data']; // Access data correctly

                // Log the user data retrieved
                Log::info('User data from API:', $userData);

                // Handle null values in the response by replacing them with 'N/A'
                $fullname = $userData['full_name'] ?? 'N/A';
                $dateofbirth = $userData['birthday'] ?? 'N/A';
                $gender = $userData['gender'] ?? 'N/A';
                $email = $userData['email'] ?? 'N/A'; // Update email from API response
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

                // Log each value before storing it in the session
                Log::info("Extracted values - Fullname: {$fullname}, Email: {$email}, Date of Birth: {$dateofbirth}, Gender: {$gender}, Phone: {$phone}, Roles: {$user_role}");

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
                return view('moredetails', compact('organization', 'organization_name', 'currentPage'));
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
        $currentPage = 'Organization';
        try {
            // Memanggil showvieworganization untuk mendapatkan data organisasi
            $organization = $this->showvieworganization($organization_name)->getData()['organization'];

            // Mengirim data organisasi ke tampilan editorganization
            Log::info('Showing edit view for organization: ' . $organization_name);
            return view('editorganization', compact('organization', 'currentPage'));
        } catch (\Exception $e) {
            Log::error('Exception occurred while showing edit view: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function editorganization(Request $request)
{


    $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session

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
            $this->saveInboxMessage($userId, 'success', 'Organization updated successfully.',  $arg_request);
            return redirect('/organization')->with('success_message', 'Organization updated successfully.');
        } else {
            Log::error('Failed to update organization. Response: ' . $response->body());
                        $this->saveInboxMessage($userId, 'error', 'Failed to update organization. Please try again',  $arg_request);
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

    // Pass the current page name to the view
    return view('personal', [
        'personalInfo' => $personalInfo,
        'currentPage' => 'Profile' // Set the current page name
    ]);
}


    public function showeditpersonal()
    {
        return view('editpersonal');
    }

    public function editpersonal(Request $request)
{


    $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session

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

        // Simpan pesan sukses ke inbox
        $this->saveInboxMessage($userId, 'success', 'profil udah update', $arg_request);
        return redirect('/personal')->with('success', 'Data has been saved!');
    } else {
        // Jika gagal, kembalikan pengguna dengan pesan error
  $message = 'Failed to save data! Please try again. Attempted data: ' .
                    'Name: ' . $request->fullname . ', ' .
                    'Username: ' . $request->username . ', ' .
                    'Birthday: ' . $request->dateofbirth;
                    'gender' . $request->gender;
                    'phone' . $request->phone;

        $this->saveInboxMessage($userId, 'error', $message, $request);
        return redirect()->back()->with('error', 'Failed to save data! Please try again.');
    }
}

    public function showuploadProfilePicture()
    {
        return view ('uploadprofile');
    }

    public function uploadProfilePicture(Request $request)
    {

        $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session


        Log::info('Starting profile picture upload process.');

        // Validate image file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profilePicturePath = null;

        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store the image in 'public/images/profile_pictures' directory
            $file->move(public_path('images/profile_pictures'), $filename);

            // Define the path to be stored in the session and used for API
            $profilePicturePath = '/images/profile_pictures/' . $filename;

            session(['profile_picture' => $profilePicturePath]);

            Log::info('File uploaded by user:', ['filename' => $filename, 'path' => $profilePicturePath]);

            try {
                // Send the relative path to the API
                $response = Http::withHeaders([
                    'Authorization' => session('access_token'),
                    'x-api-key' => self::API_KEY,
                ])
                ->post(self::API_URL . '/sso/update_profile_picture.json', [
                    'profile_image_path' => $profilePicturePath, // sending relative path
                ]);

                Log::info('API response received:', ['status' => $response->status(), 'body' => $response->body()]);

                if ($response->successful()) {
                            $this->saveInboxMessage($userId, 'success', 'Profile picture updated successfully.',  $arg_request);

                    Log::info('Profile picture updated successfully via API.', ['filename' => $filename]);

                    echo "<script>localStorage.setItem('profile_picture', '$profilePicturePath');</script>";
                    return redirect()->route('personal')->with('success', 'Profile picture uploaded successfully.');
                } else {
                    $errorMessage = $response->json()['message'] ?? 'An error occurred while uploading profile picture.';
                    Log::error('API response error:', ['status' => $response->status(), 'message' => $errorMessage]);
                                $this->saveInboxMessage($userId, 'success', 'An error occurred while uploading profile picture',  $arg_request);
                    return redirect()->back()->with('error', $errorMessage);
                }
            } catch (\Exception $e) {
                Log::error('Exception during API request:', ['exception' => $e->getMessage()]);
                return redirect()->back()->with('error', 'An error occurred during the upload process.');
            }
        } else {
            Log::warning('No valid file uploaded by user.');
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
        $currentPage = 'Security'; // Define the current page variable
        return view('security', compact('currentPage')); // Pass it to the view
    }

    public function showeditpassword()
    {
        return view ('editpassword');
    }

    public function editpassword(Request $request)
{

    $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session

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
                    $this->saveInboxMessage($userId, 'success', 'Password changed successfully',  $arg_request);
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

    public function editpersonaladm(Request $request)
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

    return redirect('/personaladm')->with('success', 'Data has been saved!');
} else {
    // Jika gagal, kembalikan pengguna dengan pesan error
    return redirect()->back()->with('error', 'Failed to save data! Please try again.');
}
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
        Log::info('Attempting to fetch user data.');

        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/sso/get_user_data.json');

        Log::info('API Response Status: ' . $response->status());

        if ($response->successful()) {
            Log::info('User data retrieved successfully.');
            return view('admin.datapengguna', ['users' => $response->json()['data']]);
        }

        Log::error('Failed to retrieve user data.');
        return response()->json(['success' => false, 'message' => 'Failed to retrieve user data.'], $response->status());

    } catch (\Exception $e) {
        Log::error('Error retrieving user data: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}

public function showuserrole()
{
    Log::info('Fetching user roles data.');

    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => session('access_token'),
    ])->get(self::API_URL . '/sso/get_role_data.json');

    Log::info('API Response Status: ' . $response->status());

    if ($response->successful()) {
        Log::info('User roles data retrieved successfully.');
        $roles = $response->json('roles');
        return view('admin.userrole', compact('roles'));
    } else {
        Log::error('Failed to fetch roles data.');
        return back()->withErrors('Failed to fetch roles data.');
    }
}

public function showcreaterole()
{
    Log::info('Showing create role view.');
    return view('admin.createrole');
}

public function createrole(Request $request)
{
    Log::info('Attempting to create a new role.');

    $request->validate([
        'role' => 'required|string|max:255',
    ]);

    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => session('access_token'),
    ])->post(self::API_URL . '/sso/create_role.json', [
        'role' => $request->input('role'),
    ]);

    Log::info('API Response Status: ' . $response->status());

    if ($response->successful()) {
        Log::info('Role created successfully.');
        return redirect()->route('showuserrole')->with('success', 'Role created successfully!');
    }

    Log::error('Failed to create role.');
    return redirect()->route('showcreaterole')->with('error', 'Failed to create role.');
}

public function showupdaterole($idrole)
{
    Log::info('Fetching role data for editing. Role ID: ' . $idrole);

    $response = Http::withHeaders([
        'x-api-key' => self::API_KEY,
        'Authorization' => session('access_token'),
    ])->get(self::API_URL . '/sso/get_role_by_id.json', ['id' => $idrole]);

    Log::info('API Response Status: ' . $response->status());

    if ($response->successful()) {
        Log::info('Role data retrieved successfully for Role ID: ' . $idrole);
        $role = $response->json();
        return view('admin.editrole', ['role' => $role]);
    }

    Log::error('Failed to fetch role data for Role ID: ' . $idrole);
    return redirect()->route('showuserrole')->with('error', 'Failed to fetch role data.');
}

public function updateRole(Request $request)
{
    Log::info('Attempting to update role.');

    $request->validate([
        'id' => 'required|integer',
        'role_name' => 'required|string|max:255',
    ]);

    $data = [
        'id' => $request->id,
        'role_name' => $request->role_name,
    ];

    Log::info('Updating role with data:', $data);

    $response = Http::withHeaders([
        'Authorization' => session('access_token'),
        'x-api-key' => self::API_KEY,
    ])->post(self::API_URL . '/sso/update_role.json', $data);

    Log::info('API Response Status: ' . $response->status());

    if ($response->successful()) {
        Log::info('Role updated successfully.');
        return redirect()->route('showuserrole')->with('success', 'Role updated successfully!');
    }

    Log::error('Failed to update role.');
    return redirect()->route('showupdaterole', ['idrole' => $request->id])->with('error', 'Failed to update role.');
}

public function showmoredetailsadm(Request $request)
{
    Log::info('Attempting to show more user details.');

    $request->validate([
        'email' => 'required|email'
    ]);

    $email = $request->input('email');
    Log::info('Retrieving details for email: ' . $email);

    try {
        $payload = ['email' => $email];

        $response = Http::withHeaders([
            'x-api-key' => self::API_KEY
        ])->post(self::API_URL  . '/sso/get_user_details_by_email.json', $payload);

        Log::info('API Response Status: ' . $response->status());
        Log::info('API Response Body: ' . $response->body());

        if ($response->successful()) {
            $userData = $response->json()['data'];
            Log::info('User details retrieved successfully for: ' . $email);

            $fullname = $userData['full_name'] ?? 'N/A';
            $dateofbirth = $userData['birthday'] ?? 'N/A';
            $gender = isset($userData['gender']) && $userData['gender'] === 0 ? 'Female' : 'Male';
            $phone = $userData['phone'] ?? 'N/A';
            $emails = $userData['email'] ?? 'N/A';
            $roles = !empty($userData['role']['id_role']) ? $userData['role']['id_role'] : 'N/A';
            $userId = $userData['user_id'] ?? null;
            $user_name = $userData['username'] ?? 'N/A';

            session([
                'user_id' => $userId,
                'fullname' => $fullname,
                'dateofbirths' => $dateofbirth,
                'genders' => $gender,
                'emails' => $emails,
                'phones' => $phone,
                'user_roles' => $roles,
                'user_name' => $user_name,
            ]);

            return view('admin.moredetails', compact('fullname', 'dateofbirth', 'gender', 'phone', 'emails', 'roles', 'user_name'));
        } else {
            Log::error('Failed to get user details from API for email: ' . $email);
            return back()->with('error', 'Failed to get user details from API: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while retrieving user details: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

// public function showProducts(Request $request)
// {
//     try {
//         // Lakukan permintaan GET ke API eksternal
//         Log::info('Requesting product data from API: ' . self::API_URL . '/product/product_show.json');

//         $response = Http::withHeaders([
//             'Authorization' => session('access_token'),
//             'x-api-key' => self::API_KEY,
//         ])->get(self::API_URL . '/product/product_show.json');

//         // Log response untuk debugging
//         Log::info('API Response: ' . $response->body());

//         // Periksa jika response berhasil
//         if ($response->successful()) {
//             $products = $response->json()['data'] ?? [];
//             Log::info('Product data retrieved successfully, total products: ' . count($products));

//             // Kirim data ke view `products`
//             return view('admin.products', ['products' => $products]);
//         }

//         Log::error('API request failed with status ' . $response->status() . ': ' . $response->body());
//         return redirect()->back()->with('error', 'Failed to retrieve product data.');

//     } catch (\Exception $e) {
//         Log::error('Exception while retrieving product data: ' . $e->getMessage());
//         return redirect()->back()->with('error', 'Error retrieving data');
//     }
// }

public function showProducts(Request $request)
{
    try {
        Log::info('Requesting product data from API: ' . self::API_URL . '/product/product_show.json');

        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_show.json');

        Log::info('API Response: ' . $response->body());

        if ($response->successful()) {
            $products = $response->json()['data'] ?? [];
            Log::info('Product data retrieved successfully, total products: ' . count($products));

            // Mengirimkan data produk ke view `admin.products`
            return view('admin.products', ['products' => $products]);
        }

        Log::error('API request failed with status ' . $response->status() . ': ' . $response->body());
        return redirect()->back()->with('error', 'Failed to retrieve product data.');

    } catch (\Exception $e) {
        Log::error('Exception while retrieving product data: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error retrieving data');
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
            $productU = $response->json()['data'] ?? []; // Default to an empty array if 'data' is not set
            return view('admin.productRelease', ['products' => $productU]);
        }

        Log::error('API request failed: ' . $response->body());
        return response()->json(['success' => false, 'message' => 'Failed to retrieve product data.'], $response->status());

    } catch (\Exception $e) {
        Log::error('Error retrieving data: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}

public function showProductsUser(Request $request)
{
    try {
        // Make the GET request to the external API using the constants
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_user_show.json');

        // Log the API response for debugging
        Log::info('API Response: ' . $response->body());

        // Check if the response was successful
        if ($response->successful()) {
            $productU = $response->json()['data'] ?? []; // Default to an empty array if 'data' is not set
            return view('admin.productUser', ['products' => $productU]);
        }

        Log::error('API request failed: ' . $response->body());
        return response()->json(['success' => false, 'message' => 'Failed to retrieve product data.'], $response->status());

    } catch (\Exception $e) {
        Log::error('Error retrieving data: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
    }
}

public function showEditProductForm($id)
{
    try {
        Log::info('Requesting product data from API: ' . self::API_URL . '/product/get_products.json');
        Log::info("Requesting product data for product ID: {$id}");

        $accessToken = session('access_token');
        if (!$accessToken) {
            Log::error("No access token found in session.");
            return redirect()->route('showProducts')->with('error', 'Unauthorized access');
        }

        $response = Http::withHeaders([
            'Authorization' => $accessToken,
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/product/get_products.json', [
            'find' => $id,
        ]);

        Log::info('API Response for product ID ' . $id . ': ' . $response->body());

        if ($response->successful() && isset($response->json()['data'])) {
            $product = $response->json()['data'][0];
            Log::info("Product data for ID {$id} retrieved successfully.");

            // Store product details in the session
            session([
                'product_id' => $product['product_id'] ?? null,
                'product_code' => $product['product_code'] ?? 'N/A',
                'product_name' => $product['product_name'] ?? 'N/A',
                'product_repository_id' => $product['product_repository_id'] ?? 'N/A',
                'enabled' => $product['enabled'] ?? false,
                'created_by' => $product['created_by'] ?? 'N/A',
                'created_date' => $product['created_date'] ?? null,
                'last_update' => $product['last_update'] ?? null,
                'description' => $product['description'] ?? 'N/A',
                'price' => $product['price'] ?? 0,
                'product_type' => $product['product_type'] ?? null,
                'logo_path' => $product['logo_path'] ?? null,
                'display_paths' => $product['display_paths'] ?? [],
            ]);

            return view('admin.edit_product', compact('product', 'id'));
        }

        Log::error("Failed to retrieve product with status " . $response->status() . ": " . $response->body());
        return redirect()->route('showProducts')->with('error', 'Product not found');
    } catch (\Exception $e) {
        Log::error("Exception while retrieving product with ID {$id}: " . $e->getMessage());
        return redirect()->route('showProducts')->with('error', 'Error retrieving product data');
    }
}

public function updateProduct(Request $request)
{
    $arg_request = $request->all(); // Mengambil seluruh data dari request body

    $userId = session('user_id'); // Mengambil user_id dari session

    Log::info('Received product update request:', $request->all());

    try {
        // Convert 'enabled' to boolean
        $request->merge(['enabled' => $request->has('enabled')]);

        // Validate input
        $request->validate([
            'product_id' => 'required|integer',
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:50|alpha_dash',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0|max:99999999.999',
            'product_type' => 'required|integer',
            'enabled' => 'required|boolean',
        ]);

        // Handle logo upload directly to public/images
        $logoPath = session('logo_path'); // Default to existing path
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $fileName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('images'), $fileName);
            $logoPath = '/images/' . $fileName; // Set the new path
        }

        // Handle display images upload directly to public/images
        $displayPaths = session('display_paths', []); // Default to existing paths
        if ($request->hasFile('display_images')) {
            $displayPaths = []; // Reset the array if new images are provided
            foreach ($request->file('display_images') as $image) {
                if ($image->isValid()) {
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images'), $fileName);
                    $displayPaths[] = '/images/' . $fileName;
                }
            }
        }

        // Format price if present
        $price = $request->price ? number_format((float)$request->price, 3, '.', '') : null;

        // Prepare data for API request
        $data = [
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'description' => $request->description,
            'price' => $price,
            'product_type' => $request->product_type,
            'enabled' => $request->enabled,
            'logo_path' => $logoPath,
            'display_paths' => $displayPaths,
        ];

        // Send request to API
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->timeout(10)
          ->post(self::API_URL . '/product/update_product.json', $data);

          if ($response->successful()) {
            Log::info('Product updated successfully.');
            $this->saveInboxMessage($userId, 'success', 'Product updated successfully.',  $arg_request);
            return redirect()->route('showProducts')->with('success_message', 'Product updated successfully.');
        } else {
            Log::error('Failed to update product. Response: ' . $response->body());
            $this->saveInboxMessage($userId, 'error', 'Failed to update product. Please try again.',  $arg_request);
            return redirect()->back()->with('error', 'Failed to update product. Please try again.');
        }
    } catch (ValidationException $e) {
        Log::error('Validation failed:', $e->errors());
        $this->saveInboxMessage($userId, 'error', 'Validation failed.', $arg_request);
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('An unexpected error occurred during product update:', ['message' => $e->getMessage()]);
        $this->saveInboxMessage($userId, 'error', 'An unexpected error occurred. Please try again.', $arg_request);
        return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
    }
}


    // Method untuk menampilkan form create product
    public function createProductForm()
    {
        return view('admin.create_product');
    }

    // Method untuk menyimpan produk baru
    public function storeProduct(Request $request)
{
    Log::info('Received product creation request:', $request->all());

    try {
        // Convert 'enabled' to boolean
        $request->merge(['enabled' => $request->has('enabled')]);

        // Validate input, including file types for logo and display images/videos
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:50|alpha_dash',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0|max:99999999.999',
            'product_type' => 'required|integer',
            'enabled' => 'required|boolean',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:20480', // Allow image and video formats
            'display_images' => 'nullable|array',
            'display_images.*' => 'file|mimes:jpg,jpeg,png,gif,mp4|max:20480', // Allow image and video formats
        ]);

        // Store logo and get path
        $logoPath = null;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $fileName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('media'), $fileName); // Store in 'media' directory
            $logoPath = '/media/' . $fileName; // Path to use for access
            Log::info('Logo path stored:', ['path' => $logoPath]);
        } else {
            Log::warning('No logo file uploaded or file is not valid.');
        }

        // Store display files (images/videos) and get paths if provided
        $displayPaths = [];
        if ($request->hasFile('display_images')) {
            foreach ($request->file('display_images') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('media'), $fileName); // Store in 'media' directory
                    $displayPaths[] = '/media/' . $fileName; // Path to use for access
                    Log::info('Display file path stored:', ['path' => '/media/' . $fileName]);
                } else {
                    Log::warning('Invalid display file.');
                }
            }
        } else {
            Log::warning('No display files uploaded.');
        }

        // Format price if present
        $price = $request->price ? number_format((float)$request->price, 3, '.', '') : null;

        // Prepare data for API request
        $data = [
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'description' => $request->description,
            'price' => $price,
            'product_type' => $request->product_type,
            'enabled' => $request->enabled,
            'logo_path' => $logoPath,
            'display_paths' => $displayPaths,
        ];

        Log::info('Prepared data for API request:', $data);

        // Send request to API
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->timeout(10)
          ->post(self::API_URL . '/product/create_product.json', $data);

        if ($response->successful()) {
            Log::info('Product created successfully.');
            Log::info('API Response:', ['response' => $response->body()]);
            return redirect()->route('showProducts')->with('success', 'Product created successfully.');
        } else {
            $status = $response->status();
            $errorMessage = $response->json('message') ?? 'Unknown error';
            $responseBody = $response->body(); // Log the raw response body
            Log::error("Failed to create product. Status: {$status}, Error: {$errorMessage}, Response: {$responseBody}");
            return redirect()->back()->with('error', 'Failed to create product. Please try again.');
        }

    } catch (ValidationException $e) {
        Log::error('Validation failed :', $e->errors());
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('An unexpected error occurred during product creation:', ['message' => $e->getMessage()]);
        return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
    }
}


    public function showTransaction(Request $request)
    {
        try {
            // Make the GET request to the external API
            $response = Http::withHeaders([
                        'Authorization' => session('access_token'),
                        'x-api-key' => self::API_KEY, // Ensure API_KEY is set in your .env file
                    ])->get(self::API_URL .  '/product/transactions_show.json');

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

public function showPaymentHistory(Request $request)
{
    try {
        // Make the GET request to the external API
        $response = Http::withHeaders([
                    'Authorization' => session('access_token'),
                    'x-api-key' => self::API_KEY, // Ensure API_KEY is set in your .env file
                ])->get(self::API_URL .  '/finance/receipt_show.json');

        if ($response->successful()) {
            return view('admin.datapembayaran', [
                'receipt' => $response->json()['data'] // Mengubah 'users' menjadi 'transactions'
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

public function showOrganizations()
{
    Log::info('Attempting to fetch organizations for admin view.');

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
        ])->post(self::API_URL . '/sso/list_all_organizations.json');

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

            // Log the number of organizations fetched
            Log::info('Fetched organizations count: ' . count($organizations));

            // Prepare the data for the view
            return view('admin.organizations', compact('organizations'));
        } else {
            // Log error if either API fails
            Log::error('Failed to get organization list. Owner Response: ' . $ownerResponse->body() . ', Member Response: ' . $memberResponse->body());
            return back()->with('error', 'Failed to get organization list. Please try again.');
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while fetching organizations: ' . $e->getMessage());
        return back()->with('error', $e->getMessage());
    }
}

public function showDetailOrganization($id)
{
    // Call the external API to get the organization details
    try {
        // Fetch organization details and members
        $organizationResponse = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/sso/get_organization_detail.json', [
            'id' => $id,
        ]);

        // Log the response for debugging
        Log::info('Organization Response: ' . $organizationResponse->body());

        // Check if the API response is successful
        if ($organizationResponse->successful()) {
            $data = $organizationResponse->json()['data']; // Get the organization data
            $organization = [
                'id' => $data['id'],
                'organization_name' => $data['organization_name'],
                'description' => $data['description'],
            ];
            $members = $data['members'] ?? []; // Get the members data, default to empty array if not found
        } else {
            Log::error('Failed to fetch organization ID ' . $id . ': ' . $organizationResponse->body());
            return redirect()->back()->with('error', 'Organization not found.');
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while fetching organization ID ' . $id . ': ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while fetching organization details.');
    }

    // Pass the organization and members data to the view
    return view('admin.detailorganization', compact('organization', 'members'));
}

public function deactivateUser(Request $request)
{
    $userId = session('user_id'); // Get the user_id directly from session

    if (!$userId) {
        return response()->json(['success' => false, 'message' => 'User ID not found in session.']);
    }

    try {
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/sso/deactive_user.json', [
            'user_id' => $userId,
        ]);

        Log::info('Deactivation API Response: ' . $response->body());

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('showuserdata'), // Return redirect URL
                'message' => 'User deactivated successfully.'
            ]);
        } else {
            return back()->with('error', 'Failed to deactivate user. ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Error deactivating user: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

    public function getLicenseData(Request $request)
    {
        return $this->fetchData('license/get_license_data.json', 'admin.license_data');
    }

    /**
     * Fetch and display Activity Data.
     */
    public function getActivityData(Request $request)
    {
        return $this->fetchData('license/get_activity_data.json', 'admin.activity_data');
    }

    /**
     * Fetch and display Hooks Data.
     */
    public function getHooksData(Request $request)
    {
        return $this->fetchData('license/get_hooks_data.json', 'admin.hooks_data');
    }

    /**
     * Fetch and display License Order Data.
     */
    public function getLicenseOrderData(Request $request)
    {
        return $this->fetchData('license/get_license_order_data.json', 'admin.license_order_data');
    }

    /**
     * Fetch and display Serial Number Data.
     */
    public function getSerialNumberData(Request $request)
    {
        return $this->fetchData('license/get_serial_number_data.json', 'admin.serial_number_data');
    }

    public function fetchData($endpoint, $viewName)
    {
        try {
            // Make the GET request to the external API
            $response = Http::withHeaders([
                'Authorization' => session('access_token'), // API token if needed
                'x-api-key' => self::API_KEY,
            ])->get(self::API_URL . '/' . $endpoint);

            // Log the API response for debugging
            Log::info('API Response for ' . $endpoint . ': ' . $response->body());

            // Check if the response was successful
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                return view($viewName, ['data' => $data]);
            }

            // Log and return an error response if the API request failed
            Log::error('API request failed: ' . $response->body());
            return response()->json(['success' => false, 'message' => 'Failed to retrieve data.'], $response->status());

        } catch (\Exception $e) {
            // Log and return an error response if an exception occurred
            Log::error('Error retrieving data: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error retrieving data'], 500);
        }
    }

    public function licensedetails(Request $request)
{
    Log::info('Attempting to show more license details.');

    // Validate the request to ensure an 'id' is provided
    $request->validate([
        'id' => 'required|integer'
    ]);

    $id = $request->input('id');  // Get the license ID from the request
    Log::info('Retrieving details for license ID: ' . $id);

    try {
        // Prepare the payload for the API request
        $payload = [
            'id' => $id
        ];

        // Send the POST request to the new API endpoint
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),  // Static authorization token
            'x-api-key' => self::API_KEY  // API key from config
        ])->post(self::API_URL . '/license/get_license_data_by_id.json', $payload);

        Log::info('API Response Status: ' . $response->status());
        Log::info('API Response Body: ' . $response->body());

        if ($response->successful()) {
            // Assuming the API response contains a 'data' field with license details
            $licenseData = $response->json()['data'] ?? null;

            // For debugging purposes
            dd($licenseData);  // Dump the raw API response for testing

            if ($licenseData) {
                Log::info('License details retrieved successfully for ID: ' . $id);

                // Extract license details
                $licenseKey = $licenseData['license_key'] ?? 'N/A';
                $licenseType = $licenseData['license_type'] ?? 'N/A';
                $createdDate = $licenseData['created_date'] ?? 'N/A';
                $activatedDate = $licenseData['activated_date'] ?? 'N/A';
                $expiredDate = $licenseData['expired_date'] ?? 'N/A';
                $notes = $licenseData['notes'] ?? 'N/A';

                // Store license details in the session (if necessary)
                session([
                    'license_key' => $licenseKey,
                    'license_type' => $licenseType,
                    'created_date' => $createdDate,
                    'activated_date' => $activatedDate,
                    'expired_date' => $expiredDate,
                    'notes' => $notes,
                ]);

                // Return the view with license details
                return view('admin.license_data', compact('licenseKey', 'licenseType', 'createdDate', 'activatedDate', 'expiredDate', 'notes'));
            } else {
                Log::error('No license data found for ID: ' . $id);
                return back()->with('error', 'No license data found for the provided ID.');
            }
        } else {
            Log::error('Failed to get license details from API for ID: ' . $id);
            return back()->with('error', 'Failed to get license details from API: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while retrieving license details: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}


public function activitydetails(Request $request)
{
    Log::info('Attempting to show more activity details.');

    // Validate the request to ensure an 'id' is provided
    $request->validate([
        'id' => 'required|integer'
    ]);

    $id = $request->input('id');  // Get the activity ID from the request
    Log::info('Retrieving details for activity ID: ' . $id);

    try {
        // Prepare the payload for the API request
        $payload = [
            'id' => $id
        ];

        // Send the POST request to the new API endpoint
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),  // Static authorization token
            'x-api-key' => self::API_KEY  // API key from config
        ])->post(self::API_URL . '/license/get_activity_data_by_id.json', $payload);

        Log::info('API Response Status: ' . $response->status());
        Log::info('API Response Body: ' . $response->body());

        if ($response->successful()) {
            // Assuming the API response contains a 'data' field with activity details
            $activityData = $response->json()['data'] ?? null;
            if ($activityData) {
                Log::info('Activity details retrieved successfully for ID: ' . $id);

                // Extract activity details
                $activityName = $activityData['activity_name'] ?? 'N/A';
                $activityType = $activityData['activity_type'] ?? 'N/A';
                $startDate = $activityData['start_date'] ?? 'N/A';
                $endDate = $activityData['end_date'] ?? 'N/A';
                $description = $activityData['description'] ?? 'N/A';

                // Store activity details in the session (if necessary)
                session([
                    'activity_name' => $activityName,
                    'activity_type' => $activityType,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'description' => $description,
                ]);

                // Return the view with activity details
                return view('admin.activity_data', compact('activityName', 'activityType', 'startDate', 'endDate', 'description'));
            } else {
                Log::error('No activity data found for ID: ' . $id);
                return back()->with('error', 'No activity data found for the provided ID.');
            }
        } else {
            Log::error('Failed to get activity details from API for ID: ' . $id);
            return back()->with('error', 'Failed to get activity details from API: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while retrieving activity details: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

public function hooksdetails(Request $request)
{
    Log::info('Attempting to show more hooks data.');

    // Validate the request to ensure an 'id' is provided
    $request->validate([
        'id' => 'required|integer'
    ]);

    $id = $request->input('id');  // Get the hooks data ID from the request
    Log::info('Retrieving details for hooks data ID: ' . $id);

    try {
        // Prepare the payload for the API request
        $payload = [
            'id' => $id
        ];

        // Send the POST request to the new API endpoint
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),  // Static authorization token
            'x-api-key' => self::API_KEY  // API key from config
        ])->post(self::API_URL . '/license/get_hooks_data_by_id.json', $payload);

        Log::info('API Response Status: ' . $response->status());
        Log::info('API Response Body: ' . $response->body());

        if ($response->successful()) {
            // Assuming the API response contains a 'data' field with hooks details
            $hooksData = $response->json()['data'] ?? null;
            if ($hooksData) {
                Log::info('Hooks data retrieved successfully for ID: ' . $id);

                // Extract hooks data details
                $hookName = $hooksData['hook_name'] ?? 'N/A';
                $hookType = $hooksData['hook_type'] ?? 'N/A';
                $hookStatus = $hooksData['hook_status'] ?? 'N/A';
                $createdAt = $hooksData['created_at'] ?? 'N/A';
                $updatedAt = $hooksData['updated_at'] ?? 'N/A';

                // Store hooks data details in the session (if necessary)
                session([
                    'hook_name' => $hookName,
                    'hook_type' => $hookType,
                    'hook_status' => $hookStatus,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                // Return the view with hooks data details
                return view('admin.hooks_data', compact('hookName', 'hookType', 'hookStatus', 'createdAt', 'updatedAt'));
            } else {
                Log::error('No hooks data found for ID: ' . $id);
                return back()->with('error', 'No hooks data found for the provided ID.');
            }
        } else {
            Log::error('Failed to get hooks data from API for ID: ' . $id);
            return back()->with('error', 'Failed to get hooks data from API: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while retrieving hooks data: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

public function orderlicensedetails(Request $request)
{
    Log::info('Attempting to show more license order data.');

    // Validate the request to ensure an 'id' is provided
    $request->validate([
        'id' => 'required|integer'
    ]);

    $id = $request->input('id');  // Get the license order ID from the request
    Log::info('Retrieving license order data for ID: ' . $id);

    try {
        // Prepare the payload for the API request
        $payload = [
            'id' => $id
        ];

        // Send the POST request to the new API endpoint
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),  // Static authorization token
            'x-api-key' => self::API_KEY  // API key from config
        ])->post(self::API_URL . '/license/get_license_order_data_by_id.json', $payload);

        Log::info('API Response Status: ' . $response->status());
        Log::info('API Response Body: ' . $response->body());

        if ($response->successful()) {
            // Assuming the API response contains a 'data' field with license order details
            $licenseOrderData = $response->json()['data'] ?? null;
            if ($licenseOrderData) {
                Log::info('License order data retrieved successfully for ID: ' . $id);

                // Extract relevant license order data details
                $orderNumber = $licenseOrderData['order_number'] ?? 'N/A';
                $licenseKey = $licenseOrderData['license_key'] ?? 'N/A';
                $orderStatus = $licenseOrderData['order_status'] ?? 'N/A';
                $purchaseDate = $licenseOrderData['purchase_date'] ?? 'N/A';
                $expirationDate = $licenseOrderData['expiration_date'] ?? 'N/A';

                // Store license order data in the session (if necessary)
                session([
                    'order_number' => $orderNumber,
                    'license_key' => $licenseKey,
                    'order_status' => $orderStatus,
                    'purchase_date' => $purchaseDate,
                    'expiration_date' => $expirationDate,
                ]);

                // Return the view with license order data details
                return view('admin.license_order_data', compact('orderNumber', 'licenseKey', 'orderStatus', 'purchaseDate', 'expirationDate'));
            } else {
                Log::error('No license order data found for ID: ' . $id);
                return back()->with('error', 'No license order data found for the provided ID.');
            }
        } else {
            Log::error('Failed to get license order data from API for ID: ' . $id);
            return back()->with('error', 'Failed to get license order data from API: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while retrieving license order data: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

public function serialnumberdetails(Request $request)
{
    Log::info('Attempting to show more serial number data.');

    // Validate the request to ensure an 'id' is provided
    $request->validate([
        'id' => 'required|integer'
    ]);

    $id = $request->input('id');  // Get the serial number ID from the request
    Log::info('Retrieving serial number data for ID: ' . $id);

    try {
        // Prepare the payload for the API request
        $payload = [
            'id' => $id
        ];

        // Send the POST request to the new API endpoint
        $response = Http::withHeaders([
            'Authorization' => '0f031be1caef52cfc46ecbb8eee10c77',  // Static authorization token
            'x-api-key' => self::API_KEY  // API key from config
        ])->post(self::API_URL . '/license/get_serial_number_data_by_id.json', $payload);

        Log::info('API Response Status: ' . $response->status());
        Log::info('API Response Body: ' . $response->body());

        if ($response->successful()) {
            // Assuming the API response contains a 'data' field with serial number details
            $serialNumberData = $response->json()['data'] ?? null;
            if ($serialNumberData) {
                Log::info('Serial number data retrieved successfully for ID: ' . $id);

                // Extract relevant serial number data details
                $serialNumber = $serialNumberData['serial_number'] ?? 'N/A';
                $licenseKey = $serialNumberData['license_key'] ?? 'N/A';
                $status = $serialNumberData['status'] ?? 'N/A';
                $assignedDate = $serialNumberData['assigned_date'] ?? 'N/A';
                $expirationDate = $serialNumberData['expiration_date'] ?? 'N/A';

                // Store serial number data in the session (if necessary)
                session([
                    'serial_number' => $serialNumber,
                    'license_key' => $licenseKey,
                    'status' => $status,
                    'assigned_date' => $assignedDate,
                    'expiration_date' => $expirationDate,
                ]);

                // Return the view with serial number data details
                return view('admin.serial_number_data', compact('serialNumber', 'licenseKey', 'status', 'assignedDate', 'expirationDate'));
            } else {
                Log::error('No serial number data found for ID: ' . $id);
                return back()->with('error', 'No serial number data found for the provided ID.');
            }
        } else {
            Log::error('Failed to get serial number data from API for ID: ' . $id);
            return back()->with('error', 'Failed to get serial number data from API: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('Exception occurred while retrieving serial number data: ' . $e->getMessage());
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
public function showProductsu(Request $request)
{
    try {
        Log::info('Requesting product data from API: ' . self::API_URL . '/product/product_show.json');

        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->get(self::API_URL . '/product/product_show.json');

        Log::info('API Response: ' . $response->body());

        if ($response->successful()) {
            $products = $response->json()['data'] ?? [];
            Log::info('Product data retrieved successfully, total products: ' . count($products));

            // Pass the products data and current page name to the view
            return view('product', [
                'products' => $products,
                'currentPage' => 'Products' // Set the current page name
            ]);
        }

        Log::error('API request failed with status ' . $response->status() . ': ' . $response->body());
        return redirect()->back()->with('error', 'Failed to retrieve product data.');

    } catch (\Exception $e) {
        Log::error('Exception while retrieving product data: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error retrieving data');
    }
}

public function showDetailProductu($id)
{
    try {
        Log::info("Requesting product data for product ID: {$id} from API.");

        $accessToken = session('access_token');
        if (!$accessToken) {
            Log::error("No access token found in session.");
            return redirect()->route('showProducts')->with('error', 'Unauthorized access');
        }

        $response = Http::withHeaders([
            'Authorization' => $accessToken,
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/product/get_products.json', [
            'find' => $id,
        ]);

        Log::info('API Response for product ID ' . $id . ': ' . $response->body());

        if ($response->successful() && isset($response->json()['data'])) {
            $product = $response->json()['data'][0];

            // Extract images based on their type (e.g., 'logo' and 'display')
            $logo = null;
            $displayImages = [];

            // Check if logo images exist
            if (isset($product['logo_images']) && is_array($product['logo_images'])) {
                $logo = $product['logo_images'][0]['image_path']; // Assuming one logo image
            }

            // Check if display images exist
            if (isset($product['display_images']) && is_array($product['display_images']) && count($product['display_images']) > 0) {
                $displayImages = $product['display_images'];
            }

            // If no logo image is found, set a default logo
            if (!$logo) {
                $logo = asset('img/default-logo.png'); // Replace with your default logo path
            }

            return view('productdetail', compact('product', 'logo', 'displayImages'), [
                'currentPage' => 'Product Detail'
            ]);
        }

        Log::error("Failed to retrieve product with status " . $response->status() . ": " . $response->body());
        return redirect()->route('showProducts')->with('error', 'Product not found');
    } catch (\Exception $e) {
        Log::error("Exception while retrieving product with ID {$id}: " . $e->getMessage());
        return redirect()->route('showProducts')->with('error', 'Error retrieving product data');
    }
}



public function saveInboxMessage($userId, $type, $message, $arg_request)
{
    // Menyimpan pesan ke database atau API dengan mengirimkan arg_request sebagai data tambahan
    $data = [
        'user_id' => $userId,
        'type' => $type,
        'message' => $message,
        'request_data' => json_encode($arg_request), // Menyertakan data request sebagai JSON
    ];

    $response = Http::withHeaders([
        'Authorization' => session('access_token'),
        'x-api-key' => self::API_KEY,
    ])
    ->timeout(10)
    ->post(self::API_URL . '/sso/get_inbox.json', $data);

    // Pastikan untuk menangani respons dari API
    if ($response->successful()) {
        Log::info("Message saved to inbox for user $userId");
    } else {
        Log::error("Failed to save message to inbox: " . $response->body());
    }
}

public function userinbox()
{
    $currentPage = 'Inbox'; // Define the current page variable
    return view('inbox', compact('currentPage')); // Pass it to the view
}

public function showInbox()
{
    try {
        Log::info('Requesting inbox data from API: ' . self::API_URL . '/sso/show_inbox.json');

        // Ambil user_id dari session
        $userId = session('user_id');
        $currentPage = 'Inbox';

        // Ambil response API
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])
        ->timeout(10)
        ->get(self::API_URL . '/sso/show_inbox.json', ['user_id' => $userId]);

        Log::info('API Response: ' . $response->body());

        // Validasi respons API
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['success'] && isset($responseData['data']) && is_array($responseData['data'])) {
                $messages = [];

                // Mengumpulkan semua pesan dalam satu array
                foreach ($responseData['data'] as $dateGroup) {
                    foreach ($dateGroup['messages'] as $message) {
                        $messages[] = $message;  // Mengumpulkan pesan berdasarkan tanggal
                    }
                }

                Log::info('Inbox data retrieved successfully, total valid messages: ' . count($messages));

                // Simpan pesan ke session
                session(['messages' => $messages]);

                // Kirim data ke view
                return view('inbox')->with('messages', $messages)->with('currentPage', $currentPage);
            } else {
                Log::warning('No valid messages found in the response.');
                session()->forget('messages'); // Hapus session jika data kosong
                return view('inbox')->with('messages', [])->with('currentPage', $currentPage);
            }
        }

        // Respons API gagal
        Log::error('API request failed with status ' . $response->status() . ': ' . $response->body());
        return redirect()->back()->with('error', 'Failed to retrieve inbox data.');

    } catch (\Exception $e) {
        Log::error('Exception while retrieving inbox data: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error retrieving inbox data');
    }
}




public function showinboxadm()
{
    try {
        Log::info('Requesting inbox data from API: ' . self::API_URL . '/sso/show_inbox.json');

        // Ambil user_id dari session
        $userId = session('user_id');

        // Ambil response API
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])
        ->timeout(10)
        ->get(self::API_URL . '/sso/show_inbox.json', ['user_id' => $userId]);

        Log::info('API Response: ' . $response->body());

        // Validasi respons API
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['success'] && isset($responseData['data']) && is_array($responseData['data'])) {
                $messages = [];

                // Mengumpulkan semua pesan dalam satu array
                foreach ($responseData['data'] as $dateGroup) {
                    foreach ($dateGroup['messages'] as $message) {
                        $messages[] = $message;  // Mengumpulkan pesan berdasarkan tanggal
                    }
                }

                Log::info('Inbox data retrieved successfully, total valid messages: ' . count($messages));

                // Simpan pesan ke session
                session(['messages' => $messages]);

                // Kirim data ke view
                return view('admin.inbox')->with('messages', $messages);
            } else {
                Log::warning('No valid messages found in the response.');
                session()->forget('messages'); // Hapus session jika data kosong
                return view('admin.inbox')->with('messages', []);
            }
        }

        // Respons API gagal
        Log::error('API request failed with status ' . $response->status() . ': ' . $response->body());
        return redirect()->back()->with('error', 'Failed to retrieve inbox data.');

    } catch (\Exception $e) {
        Log::error('Exception while retrieving inbox data: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error retrieving inbox data');
    }
}


    public function showpayment()
        {
            $currentPage = 'Payment'; // Define the current page variable
            return view('payment', compact('currentPage')); // Pass it to the view
        }

        public function getUnreadMessages()
        {
            // Ambil user_id dari session
            $userId = session('user_id');

            // Mengambil data dari API
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/sso/check_inbox_message.json', ['user_id' => $userId]);

            // Mengambil hasil dari API
            $data = $response->json();

            // Cek apakah status success bernilai true
            $unreadMessages = isset($data['success']) && $data['success'] === true ? 1 : 0;  // Jika success true, set unreadMessages menjadi 1

            // Menyimpan status unreadMessages ke dalam session
            session(['unreadMessages' => $unreadMessages]);

            // Log status unreadMessages dan data yang diterima dari API
            Log::debug('Unread Messages Status: ' . $unreadMessages);
            Log::debug('API Response Data: ', $data);

            // Mengembalikan status unreadMessages dalam format JSON
            return response()->json(['unreadMessages' => $unreadMessages]);
        }

        public function markMessagesAsRead()
        {
            $userId = session('user_id');

            // Mengirim permintaan untuk menandai pesan sebagai sudah dibaca
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/sso/mark_inbox_message.json', ['user_id' => $userId]);

            // Mengambil hasil dari API
            $data = $response->json();

            // Debugging: Log hasil respons API
            Log::info('Mark Messages As Read Response:', ['data' => $data]);

            if ($data['success']) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'error' => 'Failed to mark messages as read'], 500);
            }
        }

        public function getUnreadMessagesadm()
        {
            // Ambil user_id dari session
            $userId = session('user_id');

            // Mengambil data dari API
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/sso/check_inbox_message.json', ['user_id' => $userId]);

            // Mengambil hasil dari API
            $data = $response->json();

            // Cek apakah status success bernilai true
            $unreadMessages = isset($data['success']) && $data['success'] === true ? 1 : 0;  // Jika success true, set unreadMessages menjadi 1

            // Menyimpan status unreadMessages ke dalam session
            session(['unreadMessages' => $unreadMessages]);

            // Log status unreadMessages dan data yang diterima dari API
            Log::debug('Unread Messages Status: ' . $unreadMessages);
            Log::debug('API Response Data: ', $data);

            // Mengembalikan status unreadMessages dalam format JSON
            return response()->json(['unreadMessages' => $unreadMessages]);
        }

        public function markMessagesAsReadadm()
        {
            $userId = session('user_id');

            // Mengirim permintaan untuk menandai pesan sebagai sudah dibaca
            $response = Http::withHeaders([
                'Authorization' => session('access_token'),
                'x-api-key' => self::API_KEY,
            ])->post(self::API_URL . '/sso/mark_inbox_message.json', ['user_id' => $userId]);

            // Mengambil hasil dari API
            $data = $response->json();

            // Debugging: Log hasil respons API
            Log::info('Mark Messages As Read Response:', ['data' => $data]);

            if ($data['success']) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'error' => 'Failed to mark messages as read'], 500);
            }
        }

        public function createTransaction(Request $request)
        {
            $arg_request = $request->all(); // Mengambil seluruh data dari request body

            $userId = session('user_id'); // Mengambil user_id dari session

            try {
                // Retrieve the access token from the session
                $accessToken = session('access_token');
                if (!$accessToken) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
                }

                // Prepare the API request body
                $apiRequestBody = [
                    'product_ids' => $request->input('product_ids'),
                ];

                // Call the API to create the transaction
                $response = Http::withHeaders([
                    'Authorization' => $accessToken,
                    'x-api-key' => self::API_KEY,
                ])->post(self::API_URL . '/product/create_transaction.json', $apiRequestBody);

                // Check if the API response is successful
                if ($response->successful()) {
                    $data = $response->json();
                    // Log the successful transaction
                    $this->saveInboxMessage($userId, 'success', 'Successfully transaction, check your transaction page for more!',  $arg_request);
                    Log::info('Transaction created successfully: ', $data);
                    // Add the new message to the response
                    return response()->json([
                        'success' => true,
                        'message' => ($data['message'] ?? 'Transaction created successfully.') . 'Your order is in the process.'
                    ]);
                } else {
                    // Log the error response for debugging
                    Log::error('Transaction creation failed: ', [
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'product_ids' => $request->input('product_ids'),
                    ]);
                    $this->saveInboxMessage($userId, 'error', 'Ups! your request product failed to buy',  $arg_request);
                    return response()->json(['success' => false, 'message' => $response->json()['message'] ?? 'Transaction creation failed.']);
                }
            } catch (\Exception $e) {
                // Log the exception for debugging
                Log::error('Error creating transaction: ' . $e->getMessage(), [
                    'product_ids' => $request->input('product_ids'),
                ]);
                return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.']);
            }
        }

        public function showUserTransactions()
        {
            // Assuming you have a way to get the access token
            $accessToken = session('access_token');
            $currentPage = 'Transactions'; // Define the current page variable

            if (!$accessToken) {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }

            // Call the API to get user transactions
            $response = Http::withHeaders([
                'Authorization' => $accessToken,
                'x-api-key' => self::API_KEY,
            ])->get(self::API_URL . '/product/transactions_user_show.json');

            if ($response->successful()) {
                // Use null coalescing operator to ensure $transactions is always an array
                $transactions = $response->json()['data'] ?? [];
                return view('transaction', compact('currentPage', 'transactions')); // Pass transactions to the view
            } else {
                return redirect()->back()->with('error', 'Failed to fetch transactions.');
            }
        }

        public function showOwnedProduct()
        {
            // Assuming you have a way to get the access token
            $accessToken = session('access_token');
            $currentPage = 'Owned Products'; // Define the current page variable

            if (!$accessToken) {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }

            // Call the API to get owned products
            $response = Http::withHeaders([
                'Authorization' => $accessToken,
                'x-api-key' => self::API_KEY,
            ])->get(self::API_URL . '/product/product_user_own.json');

            if ($response->successful()) {
                // Use null coalescing operator to ensure $products is always an array
                $products = $response->json()['data'] ?? [];
                return view('ownedproduct', compact('currentPage', 'products')); // Pass products to the view
            } else {
                return redirect()->back()->with('error', 'Failed to fetch owned products.');
            }
        }

        public function createLicense(Request $request)
{
    Log::info('Received license creation request:', $request->all());

    try {
        // Validate input
        $request->validate([
            'transaction_id' => 'required|integer',
            'license_type' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        // Prepare data for API request
        $data = [
            'transaction_id' => $request->transaction_id,
            'license_type' => $request->license_type,
            // 'notes' => $request->notes ?? 'Lisensi untuk uji coba transaksi pembelian',
        ];

        Log::info('Prepared data for API request:', $data);

        // Send request to API
        $response = Http::withHeaders([
            'Authorization' => session('access_token'), // Replace with your actual token
            'x-api-key' => self::API_KEY, // Replace with your actual API key
        ])->post(self::API_URL . '/license/create_license.json', $data);
        Log::info('Full API Response:', $response->json());


        if ($response->successful()) {
            // Assuming the response contains the license key and order key
            $licenseKey = $response->json('license_key');
            $orderKey = $response->json('order_key');
            $userEmail = $response->json('user_email'); // Extract user email from API response

            if (!$userEmail) {
                Log::error('User email not found in API response');
                return redirect()->back()->with('error', 'Failed to retrieve user email.');
            }
            // Send email to user with the license key and order key
            Mail::send('emails.license', [
                'license_key' => $licenseKey,
                'order_key' => $orderKey,
            ], function ($message) use ($userEmail) {
                $message->to($userEmail); // Use email from API response
                $message->subject('Your License Key and Order Key');
            });

            Log::info('License created and email sent successfully.');

            return redirect()->route('showtransaction')->with('success', 'License created successfully and email sent.');
        } else {
            $status = $response->status();
            $errorMessage = $response->json('message') ?? 'Unknown error';
            Log::error("Failed to create license. Status: {$status}, Error: {$errorMessage}");
            return redirect()->back()->with('error', 'Failed to create license. Please try again.');
        }

    } catch (ValidationException $e) {
        Log::error('Validation failed:', $e->errors());
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('An unexpected error occurred during license creation:', ['message' => $e->getMessage()]);
        return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
    }
}

public function resendLicense(Request $request)
{
    try {
        $request->validate([
            'transaction_id' => 'required|integer',
        ]);

        $data = [
            'transaction_id' => $request->transaction_id,
        ];

        // Kirim permintaan ke API
        $response = Http::withHeaders([
            'Authorization' => session('access_token'),
            'x-api-key' => self::API_KEY,
        ])->post(self::API_URL . '/product/get_transaction_details.json', $data);

        if ($response->successful()) {
            $licenseKey = $response->json('license_key');
            $orderKey = $response->json('order_key');
            $userEmail = $response->json('user_email'); // Pastikan API mengembalikan email pengguna

            if (!$userEmail) {
                Log::error('User email not found in API response');
                return redirect()->back()->with('error', 'Failed to retrieve user email.');
            }

            // Kirim email ke pengguna dengan lisensi yang baru
            Mail::send('emails.license', [
                'license_key' => $licenseKey,
                'order_key' => $orderKey,
            ], function ($message) use ($userEmail) {
                $message->to($userEmail); // Kirim email ke alamat pengguna
                $message->subject('Your License Key and Order Key');
            });

            return redirect()->back()->with('success', 'License resent successfully and email sent.');
        }

        return redirect()->back()->with('error', 'Failed to resend license. Please try again.');
    } catch (\Exception $e) {
        Log::error('Failed to resend license:', ['message' => $e->getMessage()]);
        return redirect()->back()->with('error', 'An error occurred while resending the license.');
    }
}


}

