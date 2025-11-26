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

        $query = User::where('role', $role);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->latest()->paginate(10)->withQueryString();
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'regex:/@gmail\.com$/i'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+]{10,20}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:admin,kasir,pemilik'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'address.max' => 'Alamat maksimal 255 karakter.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'],
        ]);

        $roleRoute = $validated['role'];
        $roleDisplay = ucfirst($validated['role']);
        
        if ($roleRoute == 'pemilik') {
            return redirect()->route('admin.pemilik.index')
                ->with('success', "✅ Akun {$roleDisplay} berhasil dibuat!");
        } elseif ($roleRoute == 'admin') {
            return redirect()->route('admin.admin.index')
                ->with('success', "✅ Akun {$roleDisplay} berhasil dibuat!");
        } else { // kasir
            return redirect()->route('admin.kasir.index')
                ->with('success', "✅ Akun {$roleDisplay} berhasil dibuat!");
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
            return redirect()->back()->with('error', '❌ Tidak dapat menghapus akun sendiri!');
        }
        
        // Prevent deletion of the last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', '❌ Tidak dapat menghapus admin terakhir!');
            }
        }
        
        $role = $user->role;
        $name = $user->name;
        $roleDisplay = ucfirst($role);
        
        $user->delete();
        
        return redirect()->back()->with('success', "✅ Akun {$roleDisplay} ({$name}) berhasil dihapus!");
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id, 'regex:/@gmail\.com$/i'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+]{10,20}$/'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'address.max' => 'Alamat maksimal 255 karakter.',
        ]);
        
        // Prepare data for update
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
            'address' => $validated['address'] ?? $user->address,
        ];
        
        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }
        
        $user->update($updateData);
        
        $role = $user->role;
        $roleDisplay = ucfirst($role);
        
        return redirect()->route('admin.' . $role . '.index')
            ->with('success', "✅ Akun {$roleDisplay} berhasil diperbarui!");
    }
}


