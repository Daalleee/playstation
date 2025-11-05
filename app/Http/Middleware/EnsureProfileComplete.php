<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if phone or address is empty
            if (empty($user->phone) || empty($user->address)) {
                return redirect()->route('pelanggan.profile.edit')
                    ->with('warning', '⚠️ Lengkapi profil Anda terlebih dahulu! Nomor HP dan alamat diperlukan untuk melakukan pemesanan.');
            }
        }
        
        return $next($request);
    }
}
