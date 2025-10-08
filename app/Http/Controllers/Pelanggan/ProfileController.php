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
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update basic info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
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

        return redirect()->route('pelanggan.profile.show')->with('status', 'Profil berhasil diperbarui');
    }
}
