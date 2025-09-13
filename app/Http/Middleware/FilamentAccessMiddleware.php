<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FilamentAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika user belum login, arahkan ke halaman login
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            // Redirect ke halaman login dashboard dengan pesan
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses dashboard admin.');
        }
        
        // Jika user sudah login tapi tidak memiliki izin
        if (!$user->hasPermissionTo('access_dashboard')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            
            // Jika user sudah login tetapi tidak memiliki izin, logout dan arahkan ke login
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Anda tidak memiliki izin untuk mengakses dashboard admin. Silakan login dengan akun administrator.');
        }

        return $next($request);
    }
}
