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
        if (! in_array($role, $validRoles)) {
            $role = 'kasir';
        }

        $users = User::where('role', $role)->latest()->get();

        return view('admin.staff.index', compact('users', 'role'));
    }

    public function create(Request $request)
    {
        Gate::authorize('access-admin');

        $role = $request->query('role');
        $validRoles = ['admin', 'kasir', 'pemilik'];
        if (! in_array($role, $validRoles)) {
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
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $roleRoute = $validated['role'];
        if ($roleRoute == 'pemilik') {
            return redirect()->route('admin.pemilik.index')
                ->with('status', 'Akun '.$validated['role'].' dibuat.');
        } elseif ($roleRoute == 'admin') {
            return redirect()->route('admin.admin.index')
                ->with('status', 'Akun '.$validated['role'].' dibuat.');
        } else { // kasir
            return redirect()->route('admin.kasir.index')
                ->with('status', 'Akun '.$validated['role'].' dibuat.');
        }
    }

    // Specific methods for each role
    public function adminIndex(Request $request)
    {
        return $this->index($request->merge(['role' => 'admin']));
    }

    public function adminCreate(Request $request)
    {
        return $this->create($request->merge(['role' => 'admin']));
    }

    public function pemilikIndex(Request $request)
    {
        return $this->index($request->merge(['role' => 'pemilik']));
    }

    public function pemilikCreate(Request $request)
    {
        return $this->create($request->merge(['role' => 'pemilik']));
    }

    public function kasirIndex(Request $request)
    {
        return $this->index($request->merge(['role' => 'kasir']));
    }

    public function kasirCreate(Request $request)
    {
        return $this->create($request->merge(['role' => 'kasir']));
    }

    public function destroy(Request $request, User $user)
    {
        Gate::authorize('access-admin');

        // Prevent deletion of current user
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $role = $user->role;
        $user->delete();

        return redirect()->back()->with('status', 'Akun '.$role.' dihapus.');
    }

    public function edit(Request $request, User $user)
    {
        Gate::authorize('access-admin');

        // Determine which role edit page to show based on the user's role
        $role = $user->role;

        return view('admin.staff.edit', compact('user', 'role'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('access-admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        // Prepare data for update
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update password if provided
        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        $role = $user->role;

        return redirect()->route('admin.'.$role.'.index')
            ->with('status', 'Akun '.$role.' diperbarui.');
    }
}
