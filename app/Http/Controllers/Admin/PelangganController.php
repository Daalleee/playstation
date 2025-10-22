<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');
        $pelanggan = User::where('role', 'pelanggan')->latest()->paginate(10);
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        Gate::authorize('access-admin');
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'regex:/@gmail\\.com$/i'],
            'password' => ['required', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\\+62[0-9]{8,20}$/'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'role' => 'pelanggan',
        ]);

        return redirect()->route('admin.pelanggan.index')->with('status', 'Pelanggan dibuat');
    }

    public function edit(User $pelanggan)
    {
        Gate::authorize('access-admin');
        abort_unless($pelanggan->role === 'pelanggan', 404);
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, User $pelanggan)
    {
        Gate::authorize('access-admin');
        abort_unless($pelanggan->role === 'pelanggan', 404);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$pelanggan->id, 'regex:/@gmail\\.com$/i'],
            'password' => ['nullable', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\\+62[0-9]{8,20}$/'],
        ]);

        $pelanggan->name = $validated['name'];
        $pelanggan->email = $validated['email'];
        $pelanggan->address = $validated['address'] ?? null;
        $pelanggan->phone = $validated['phone'] ?? null;
        if (!empty($validated['password'])) {
            $pelanggan->password = Hash::make($validated['password']);
        }
        $pelanggan->save();

        return redirect()->route('admin.pelanggan.index')->with('status', 'Pelanggan diperbarui');
    }

    public function destroy(User $pelanggan)
    {
        Gate::authorize('access-admin');
        abort_unless($pelanggan->role === 'pelanggan', 404);
        $pelanggan->delete();
        return redirect()->route('admin.pelanggan.index')->with('status', 'Pelanggan dihapus');
    }
}


