<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $activeRoleUser = $user?->roleuser?->firstWhere('status', 1) ?? $user?->roleuser?->first();
        $roleId = $activeRoleUser?->idrole ?? session('user_role');
        $roleName = strtolower(trim((string) ($activeRoleUser?->role?->nama_role ?? '')));

        if ((string) $roleId === '2' || $roleName === 'vendor') {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'You do not have vendor access.');
    }
}
