<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
         // Get the role from the session
         $userRole = session('role_id');

         // Check if the user role matches the expected role
         if ($userRole != $role) {
             // Optionally, you can redirect to a specific page or show an error
             return redirect()->route('dashboard')->withErrors(['error' => 'Access denied.']);
         }

         return $next($request);
    }
}
