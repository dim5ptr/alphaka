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
            $response = Http::withHeaders([
                'x-api-key' => self::API_KEY
            ])->post(self::API_URL . '/sso/register.json', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $data = $response->json();


            if ($response->successful() && isset($data['result'])) {
                if ($data['result'] === 1) {
                    return redirect('/verify')->with('success_message', $data['data']);
                } elseif ($data['result'] === 2) {
                    return back()->withErrors([
                        'error_message' => $data['data'],
                    ])->withInput();
                } elseif ($data['result'] === 3) {
                    return back()->withErrors([
                        'error_message' => $data['data'],
                    ])->withInput();
                } elseif ($data['result'] === 4) {
                    return back()->withErrors([
                        'error_message' => $data['data'],
                    ])->withInput();
                }
            }

        } catch (\Exception $e) {
            return back()->withErrors([
                'error_message' => 'Something wrong, try again!',
            ])->withInput();
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
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', Lang::get($response))
            : back()->withErrors(['email' => Lang::get($response)]);
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
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(64);

        DB::table('resetpassword')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.forgotPassword', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm($token)
    {
        return view('formforgetpassword', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('resetpassword')
                            ->where([
                              'email' => $request->email,
                              'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('resetpassword')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('status', 'Your password has been changed!');
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
        ]);


        $access_token = session('access_token');

        $response = Http::withHeaders([
            'Authorization' => $access_token,
            'x-api-key' => self:: API_KEY,
        ])->post(self::API_URL . '/sso/change_password.json', [
            'password' => $request->new_password,
        ]);

        if ($response->successful()) {
            // Password berhasil diubah
            Auth::logout(); // Logout pengguna
            Session::flash('success', 'Password changed successfully. Please log in again.'); // Pesan sukses
            return Redirect::route('login'); // Redirect ke halaman login

        // Ambil access token dari session
        $accessToken = session('access_token');

        // Periksa apakah access token ada
        if (!$accessToken) {
            return redirect()->route('login')->withErrors(['error' => 'Access token not found. Please login again.']);
        }

        // Periksa apakah pengguna sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->withErrors(['error' => 'User not logged in.']);
        }

        // Ambil email pengguna yang sudah login
        $userEmail = auth()->user()->email;

        // Verifikasi password lama dengan memanggil endpoint login
        $loginResponse = Http::withHeaders([
            'x-api-key' => self::API_KEY
        ])->post(self::API_URL . '/sso/login.json', [
            'username' => $userEmail, // Gunakan email pengguna yang sudah login
            'password' => $request->old_password,
        ]);

        // Periksa apakah respon login berhasil
        if ($loginResponse->successful() && isset($loginResponse['data']['access_token'])) {
            // Ganti password
                $changePasswordResponse = Http::withHeaders([
                    'Authentication' => $accessToken,
                    'x-api-key' => self::API_KEY
                ])->post(self::API_URL . '/sso/change_password.json', [
                    'password' => $request->new_password,
                ]);

                // Periksa apakah perubahan password berhasil
                if ($changePasswordResponse->successful()) {
                    session()->forget('access_token');
                    auth()->logout();


                    // Redirect dengan pesan sukses
                    return redirect()->route('login')->with('success', 'Password changed successfully. Please login again.');
                } else {
                    // Tangani kesalahan validasi atau kesalahan lain dari API
                    $errorMessages = $changePasswordResponse->json();
                    if ($changePasswordResponse->status() == 422) {
                        return back()->withErrors($errorMessages)->withInput();
                    } else {
                        // Redirect dengan pesan kesalahan
                        return back()->withErrors(['error' => 'Failed to change password. Please try again later.'])->withInput();
                    }
                }

            } else {
                // Gagal mengubah password
                Session::flash('error', 'Failed to change password. Please try again.'); // Pesan error
                return Redirect::back(); // Kembali ke halaman sebelumnya
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
