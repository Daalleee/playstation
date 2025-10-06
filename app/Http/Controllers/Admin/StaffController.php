<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-admin');

        $role = $request->query('role');
        $validRoles = ['admin', 'kasir', 'pemilik'];
        if (!in_array($role, $validRoles)) {
            $role = 'kasir';
        }

        $users = User::where('role', $role)->latest()->paginate(10);
        return view('admin.staff.index', compact('users', 'role'));
    }

    public function create(Request $request)
    {
        Gate::authorize('access-admin');

        $role = $request->query('role');
        $validRoles = ['admin', 'kasir', 'pemilik'];
        if (!in_array($role, $validRoles)) {
            $role = 'kasir';
        }

        return view('admin.staff.create', compact('role'));
    }

    public function store(Request $request)
    {
        Gate::authorize('access-admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,kasir,pemilik'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('admin.staff.index', ['role' => $validated['role']])
            ->with('status', 'Akun '.$validated['role'].' dibuat.');
    }
}


