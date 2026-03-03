<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!\Illuminate\Support\Facades\Auth::check()) {
                $user = \App\Models\User::first();
                if (!$user) {
                    $user = \App\Models\User::create([
                        'name' => 'Auto Admin',
                        'email' => 'admin@water.system',
                        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                        'email_verified_at' => now(),
                    ]);
                }
                if ($user) {
                    \Illuminate\Support\Facades\Auth::login($user);
                }
            }
        } catch (\Exception $e) {
            // Avoid crashing if database is not migrated yet
        }
        return $next($request);
    }
}
