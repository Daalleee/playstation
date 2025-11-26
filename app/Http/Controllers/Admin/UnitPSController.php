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
        $units = UnitPS::select('id', 'name', 'model', 'brand', 'serial_number', 'price_per_hour', 'stock', 'nama', 'merek', 'nomor_seri', 'harga_per_jam', 'stok', 'foto', 'kondisi')
            ->latest()
            ->withCount('rentalItems')
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
            'nomor_seri' => ['required', 'string', 'max:255', 'unique:unit_ps,nomor_seri', 'regex:/^[A-Za-z0-9]+$/'],
            'harga_per_jam' => ['required', 'numeric', 'min:0', 'max:999999'],
            'stok' => ['required', 'integer', 'min:0', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024', 'dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Tersedia,Disewa,Maintenance'],
        ], [
            'nomor_seri.regex' => 'Nomor seri hanya boleh berisi huruf dan angka.',
            'nomor_seri.unique' => 'Nomor seri sudah digunakan.',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images/unitps', 'public');
            $validated['foto'] = $path;
        } else {
            unset($validated['foto']);
        }

        // Kompatibilitas kolom lama - mapping field Indonesia ke field database
        $data = [
            'name' => $validated['nama'],
            'brand' => $validated['merek'],
            'model' => $validated['model'],
            'serial_number' => $validated['nomor_seri'],
            'price_per_hour' => $validated['harga_per_jam'],
            'stock' => $validated['stok'],
            'kondisi' => $validated['kondisi'] ?? null,
            'status' => $validated['status'],
            // Also populate Indonesian fields
            'nama' => $validated['nama'],
            'merek' => $validated['merek'],
            'nomor_seri' => $validated['nomor_seri'],
            'harga_per_jam' => $validated['harga_per_jam'],
            'stok' => $validated['stok'],
        ];

        if (isset($validated['foto'])) {
            $data['foto'] = $validated['foto'];
        }

        UnitPS::create($data);
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
            'nomor_seri' => ['required', 'string', 'max:255', 'unique:unit_ps,nomor_seri,' . $unitp->id, 'regex:/^[A-Za-z0-9]+$/'],
            'harga_per_jam' => ['required', 'numeric', 'min:0', 'max:999999'],
            'stok' => ['required', 'integer', 'min:0', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024', 'dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Tersedia,Disewa,Maintenance'],
        ], [
            'nomor_seri.regex' => 'Nomor seri hanya boleh berisi huruf dan angka.',
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

        // Kompatibilitas kolom lama - mapping field Indonesia ke field database
        $data = [
            'name' => $validated['nama'],
            'brand' => $validated['merek'],
            'model' => $validated['model'],
            'serial_number' => $validated['nomor_seri'],
            'price_per_hour' => $validated['harga_per_jam'],
            'stock' => $validated['stok'],
            'kondisi' => $validated['kondisi'] ?? null,
            'status' => $validated['status'],
            // Also populate Indonesian fields
            'nama' => $validated['nama'],
            'merek' => $validated['merek'],
            'nomor_seri' => $validated['nomor_seri'],
            'harga_per_jam' => $validated['harga_per_jam'],
            'stok' => $validated['stok'],
        ];

        if (isset($validated['foto'])) {
            $data['foto'] = $validated['foto'];
        }

        $unitp->update($data);
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
