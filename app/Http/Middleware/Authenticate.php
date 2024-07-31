<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        // Jika pengguna belum memiliki access_token dan tidak berada di halaman login
        if (!$request->session()->has('access_token') && $request->route()->getName() !== 'login') {
            return redirect()->route('login');
        }

        if ($request->session()->has('access_token') && $request->route()->getName() === 'login') {
            return redirect()->route('confirm-logout');
        }

        return $next($request);
    }
}
