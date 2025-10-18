<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use GuzzleHttp\Exception\RequestException;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login.show')->with('status', 'Sesi login Google kedaluwarsa. Silakan coba lagi.');
        } catch (RequestException $e) {
            return redirect()->route('login.show')->with('status', 'Gagal menghubungi Google: ' . $e->getMessage());
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Str::password(16),
                'role' => 'pelanggan',
            ]);
        } else {
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        }

        Auth::login($user, true);

        return redirect()->intended('/');
    }
}
