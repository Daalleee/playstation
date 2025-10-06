<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            return match ($role) {
                'admin' => redirect()->route('dashboard.admin'),
                'kasir' => redirect()->route('dashboard.kasir'),
                'pemilik' => redirect()->route('dashboard.pemilik'),
                'pelanggan' => redirect()->route('dashboard.pelanggan'),
                default => redirect()->intended('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak sesuai.',
        ])->onlyInput('email');
    }
}


