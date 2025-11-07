<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            // Definisikan aturan validasi secara manual untuk menangani format telepon Indonesia
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'regex:/@gmail\\.com$/i'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'address' => ['required', 'string', 'max:255'],
                'phone' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        // Hapus semua karakter kecuali digit dan tanda +
                        $clean = preg_replace('/[^\d+]/', '', $value);

                        // Cek apakah format dimulai dengan 62 tanpa tanda + (tidak diperbolehkan)
                        if (preg_match('/^62[0-9]{7,13}$/', $clean)) {
                            $fail('Format nomor telepon 62xxxxxxxxx tidak diperbolehkan. Gunakan format +62xxxxxxxxx atau 08xxxxxxxxx.');
                        }

                        // Cek format yang diperbolehkan
                        $isValid = false;

                        // Format: +62xxxxxxxxx (dimulai dari digit ke-3 setelah +62), total digit setelah +62 antara 7-13
                        if (preg_match('/^\+62[0-9]{7,13}$/', $clean)) {
                            $isValid = true;
                        }
                        // Format: 08xxxxxxxxx (8 setelah 0 harus digit 1-9, lalu 6-10 digit tambahan = total 7-13 digit setelah awalan 0)
                        elseif (preg_match('/^08[1-9][0-9]{6,10}$/', $clean)) {
                            $isValid = true;
                        }

                        if (! $isValid) {
                            $fail('Nomor telepon harus dalam format yang valid (contoh: +6281234567890 atau 081234567890). Minimal 7 digit, maksimal 13 digit setelah awalan.');
                        }
                    },
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Simpan password ke session sebelum melempar error
            $request->session()->flash('password', $request->password);
            $request->session()->flash('password_confirmation', $request->password_confirmation);
            throw $e;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'role' => 'pelanggan',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.pelanggan');
    }
}
