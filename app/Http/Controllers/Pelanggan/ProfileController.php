<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        Gate::authorize('access-pelanggan');
        
        $user = auth()->user();
        return view('pelanggan.profile.show', compact('user'));
    }

    public function edit()
    {
        Gate::authorize('access-pelanggan');
        
        $user = auth()->user();
        return view('pelanggan.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        Gate::authorize('access-pelanggan');
        
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+62[0-9]{8,20}$/'],
            'address' => ['required', 'string', 'max:255', 'min:5'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'phone.required' => 'Nomor HP wajib diisi untuk melakukan pemesanan.',
            'phone.regex' => 'Format nomor HP harus: +62 diikuti 8-20 digit angka (contoh: +6281234567890)',
            'address.required' => 'Alamat wajib diisi untuk melakukan pemesanan.',
            'address.min' => 'Alamat minimal 5 karakter.',
        ]);

        // Update basic info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Update password if provided
        if ($validated['password']) {
            if (!$validated['current_password'] || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Check if there's a redirect URL after update
        $redirectUrl = session('redirect_after_update');
        if ($redirectUrl) {
            session()->forget('redirect_after_update');
            return redirect($redirectUrl)->with('status', '✅ Profil berhasil diperbarui! Silakan lanjutkan pemesanan Anda.');
        }
        
        return redirect()->route('pelanggan.profile.show')->with('status', '✅ Profil berhasil diperbarui! Sekarang Anda bisa melakukan pemesanan rental.');
    }
}
