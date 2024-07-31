<?php

use App\Http\Controllers\HttpController;
use App\Http\Controllers\YourController; // Import YourController jika belum diimport sebelumnya
use Illuminate\Support\Facades\Route;

// Rute untuk tampilan register


// Rute untuk login
Route::middleware('auth.redirect')->group(function () {

    Route::get('/register', [HttpController::class, 'showRegister'])->name('register');
    Route::post('/register', [HttpController::class, 'register']);

    Route::get('/verify', [HttpController::class, 'showuserverify'])->name('showuserverify');
    Route::post('/verify', [HttpController::class, 'userverify'])->name('userverify');

    Route::get('/login', [HttpController::class, 'showLogin'])->name('login');
    Route::post('/login', [HttpController::class, 'login']);

    Route::get('/forgetpassword',  [HttpController::class, 'showforgetpassword'])->name('showforgetpassword');
    Route::post('/forgetpassword', [HttpController::class, 'sendResetLinkEmail'])->name('forgetpassword');

    Route::get('/formforgetpassword',  [HttpController::class, 'showformforgetpassword'])->name('showformforgetpassword');
    Route::post('/formforgetpassword', [HttpController::class, 'formforgetpassword'])->name('formforgetpassword');

    // Rute untuk menampilkan halaman reset password
    Route::get('resetpassword/{token}', [HttpController::class, 'showformforgetpassword'])->name('resetpassword');

    // Rute untuk mengirim form reset password
    Route::post('resetpassword', [HttpController::class, 'resetpassword'])->name('resetpassword');

    // Show form to request a password reset link
    Route::get('password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');

    // Handle sending the password reset link
    Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');


    });

    // Rute-rute yang memerlukan autentikasi
    Route::middleware(['auth'])->group(function () {
    // Rute untuk logout
    Route::get('/logout', [HttpController::class, 'logout'])->name('logout');
    // Rute untuk konfirmasi logout
    Route::get('/confirm-logout', [HttpController::class, 'confirmLogout'])->name('confirm-logout');

    Route::get('/', [HttpController::class, 'index'])->name('dashboard');
    Route::get('/organization', [HttpController::class, 'showaddorganization'])->name('organization');
    Route::get('/showcreateorganization', [HttpController::class, 'showcreateorganization'])->name('showcreateorganization');

    Route::get('/moredetails/{organization_name}', [HttpController::class, 'showmoredetails'])->name('showmoredetails');
    Route::post('/moredetails/{organization_name}', [HttpController::class, 'moredetails'])->name('moredetails');

    Route::post('/addorganization', [HttpController::class, 'addorganization'])->name('addorganization');
    Route::get('/vieworganization/{organization_name}', [HttpController::class, 'showvieworganization'])->name('showvieworganization');
    Route::post('/vieworganization', [HttpController::class, 'vieworganization'])->name('vieworganization');
    Route::get('/editorganization/{organization_name}', [HttpController::class, 'showeditorganization'])->name('showeditorganization');
    Route::post('/editorganization/{organization_name}', [HttpController::class, 'editorganization'])->name('editorganization');
    Route::get('/personal', [HttpController::class, 'personal'])->name('personal');
    Route::get('/editpersonal',  [HttpController::class, 'showeditpersonal'])->name('showeditpersonal');
    Route::post('/editpersonal', [HttpController::class, 'editpersonal'])->name('editpersonal');
    Route::get('/upload-profile-picture', [HttpController::class, 'showuploadProfilePicture'])->name('show.upload.profile.picture');
    Route::post('/upload-profile-picture', [HttpController::class, 'uploadProfilePicture'])->name('upload.profile.picture');

    Route::get('/security', [HttpController::class, 'showsecurity'])->name('showsecurity');
    Route::get('/editpassword',  [HttpController::class, 'showeditpassword'])->name('showeditpassword');
    Route::post('/editpassword',  [HttpController::class, 'editpassword'])->name('editpassword');

    // Tambahkan rute untuk halaman Settings
    Route::get('/settings', [YourController::class, 'settings'])->name('settings');

    Route::get('/dashboardadmin', [HttpController::class, 'showdashboardadm'])->name('showdashboardadm');
    Route::post('/dashboardadmin', [HttpController::class, 'dashboardadm'])->name('dashboardadm');

    Route::get('/userdata', [HttpController::class, 'showuserdata'])->name('showuserdata');
    Route::post('/userdata', [HttpController::class, 'userdata'])->name('userdata');
    // web.php

    Route::get('/moredetailsadm', [HttpController::class, 'showmoredetailsadm'])->name('showmoredetailsadm');
    Route::post('/moredetailsadm', [HttpController::class, 'moredetailsadm'])->name('moredetailsadm');

    Route::get('/userrole', [HttpController::class, 'showuserrole'])->name('showuserrole');
    Route::post('/userrole', [HttpController::class, 'userrole'])->name('userrole');

    Route::get('/access/{role}', [HttpController::class, 'showaccess'])->name('showaccess');
    Route::post('/access/{role}', [HttpController::class, 'access'])->name('access');

    Route::get('/personaladm', [HttpController::class, 'personaladm'])->name('personaladm');
    Route::get('/editpersonaladm',  [HttpController::class, 'showeditpersonaladm'])->name('showeditpersonaladm');
    Route::post('/editpersonaladm', [HttpController::class, 'editpersonaladm'])->name('editpersonaladm');

    Route::get('/securityadm', [HttpController::class, 'showsecurityadm'])->name('showsecurityadm');
    Route::get('/changepwadm',  [HttpController::class, 'showchangepwadm'])->name('showchangepwadm');
    Route::post('/changepwadm',  [HttpController::class, 'changepwadm'])->name('changepwadm');

    Route::get('/edituseradm',  [HttpController::class, 'showedituseradm'])->name('showedituseradm');
    Route::post('/edituseradm', [HttpController::class, 'edituseradm'])->name('edituseradm');

    Route::get('/confirmpw', [PagesController::class, 'PesanPw'])->name('confirmpw');


});

