<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function impersonate(Request $request, $userId): RedirectResponse
    {
        Gate::authorize('access-admin');
        $adminId = Auth::id();
        $user = User::findOrFail($userId);
        if ($user->id === $adminId) {
            return back()->with('error', 'Tidak bisa impersonate diri sendiri');
        }
        // Simpan user admin di session sebelum impersonate
        session(['impersonate_admin_id' => $adminId]);
        Auth::login($user, true);
        return redirect('/')->with('status', 'Berhasil impersonate sebagai ' . $user->role);
    }

    public function leaveImpersonate(Request $request): RedirectResponse
    {
        $adminId = session('impersonate_admin_id');
        if ($adminId) {
            $admin = User::find($adminId);
            Auth::login($admin, true);
            session()->forget('impersonate_admin_id');
            return redirect('/dashboard/admin')->with('status', 'Kembali ke akun admin');
        }
        return redirect('/')->with('error', 'Anda tidak sedang impersonate');
    }
}
