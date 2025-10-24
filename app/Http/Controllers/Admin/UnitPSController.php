<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UnitPSController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');
        $units = UnitPS::withCount('rentalItems')->latest()->paginate(10);
        $units = UnitPS::select('id', 'nama', 'model', 'merek', 'harga_per_jam', 'stok', 'foto', 'kondisi')
            ->latest()
            ->paginate(10);
        return view('admin.unitps.index', compact('units'));
    }

    public function create()
    {
        Gate::authorize('access-admin');
        return view('admin.unitps.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['required', 'string', 'max:255'],

            'model' => ['required', 'string', 'max:100'],
            'nomor_seri' => ['required', 'string', 'max:255', 'unique:unit_ps,nomor_seri'],
            'model' => ['required', 'string', 'max:100'],
            'nomor_seri' => ['nullable', 'string', 'max:255', 'unique:unit_ps,nomor_seri'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'nomor_seri' => ['required', 'string', 'max:255', 'unique:unit_ps,nomor_seri', 'regex:/^[0-9]+$/'],
            'harga_per_jam' => ['required', 'numeric', 'min:0', 'max:999999'],
            'stok' => ['required', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024', 'dimensions:max_width=2000,max_height=2000'],

            'kondisi' => ['nullable', 'string', 'max:255'],
        ], [
            'nomor_seri.regex' => 'Nomor seri hanya boleh berisi angka.',
            'nomor_seri.unique' => 'Nomor seri sudah digunakan.',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/unitps', 'public');
            $validated['foto'] = $path;
        } else {
            unset($validated['foto']);
        }

        // Kompatibilitas kolom lama
        $validated['name'] = $validated['nama'];
        $validated['brand'] = $validated['merek'];
        $validated['model'] = $validated['model'];
        $validated['serial_number'] = $validated['nomor_seri'];
        $validated['serial_number'] = $validated['nomor_seri'] ?? null;
        $validated['price_per_hour'] = $validated['harga_per_jam'];
        $validated['stock'] = $validated['stok'];
        $validated['condition'] = $validated['kondisi'];

        UnitPS::create($validated);
        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS dibuat');
    }

    public function edit(UnitPS $unitp)
    {
        Gate::authorize('access-admin');
        return view('admin.unitps.edit', ['unit' => $unitp]);
    }

    public function update(Request $request, UnitPS $unitp)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'merek' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:100'],
            'nomor_seri' => ['nullable', 'string', 'max:255', 'unique:unit_ps,nomor_seri,' . $unitp->id],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],

            'nomor_seri' => ['required', 'string', 'max:255', 'unique:unit_ps,nomor_seri,' . $unitp->id, 'regex:/^[0-9]+$/'],
            'harga_per_jam' => ['required', 'numeric', 'min:0', 'max:999999'],
            'stok' => ['required', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024', 'dimensions:max_width=2000,max_height=2000'],

            'kondisi' => ['nullable', 'string', 'max:255'],
        ], [
            'nomor_seri.regex' => 'Nomor seri hanya boleh berisi angka.',
            'nomor_seri.unique' => 'Nomor seri sudah digunakan.',
        ]);

        if ($request->hasFile('foto')) {
            if ($unitp->foto && Storage::disk('public')->exists($unitp->foto)) {
                Storage::disk('public')->delete($unitp->foto);
            }
            $path = $request->file('foto')->store('images/unitps', 'public');
            $validated['foto'] = $path;
        } else {
            unset($validated['foto']);
        }

        // Kompatibilitas kolom lama
        $validated['name'] = $validated['nama'];
        $validated['brand'] = $validated['merek'];
        $validated['model'] = $validated['model'];
        $validated['serial_number'] = $validated['nomor_seri'];
        $validated['serial_number'] = $validated['nomor_seri'] ?? null;
        $validated['price_per_hour'] = $validated['harga_per_jam'];
        $validated['stock'] = $validated['stok'];
        $validated['condition'] = $validated['kondisi'];

        $unitp->update($validated);
        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS diperbarui');
    }

    public function destroy(UnitPS $unitp)
    {
        Gate::authorize('access-admin');
        $hasActiveRentals = $unitp->rentalItems()
            ->whereHas('rental', function ($q) {
                $q->where('status', '!=', 'returned');
            })
            ->exists();
        if ($hasActiveRentals) {
            return redirect()->route('admin.unitps.index')->with('status', 'Unit PS tidak bisa dihapus karena masih terkait transaksi yang belum dikembalikan');
        }
        $unitp->delete();
        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS dihapus');
    }
}
