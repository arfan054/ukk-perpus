<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'anggota' => redirect()->route('anggota.dashboard'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}