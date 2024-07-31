<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Jika pengguna sudah diotentikasi dan mencoba mengakses halaman login
        if ($request->session()->has('access_token') && $request->route()->getName() === 'login') {
            return redirect()->route('confirm-logout');
        } 

        return $next($request);
    }
}
