<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AccessoryController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');
        $accessories = Accessory::select('id', 'nama', 'jenis', 'stok', 'harga_per_hari', 'gambar', 'kondisi')
            ->latest()
            ->paginate(10);
        return view('admin.accessories.index', compact('accessories'));
    }

    public function create()
    {
        Gate::authorize('access-admin');
        return view('admin.accessories.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'nama' => ['required','string','max:255'],
            'jenis' => ['required','string','max:100'],
            'stok' => ['required','integer','min:0','max:1000'],
            'harga_per_hari' => ['required','numeric','min:0','max:999999'],
            'gambar' => ['nullable','image','mimes:jpeg,jpg,png,webp','max:1024','dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images/accessories', 'public');
            $validated['gambar'] = $path;
        } else {
            unset($validated['gambar']);
        }

        // Kompatibilitas kolom lama
        $validated['name'] = $validated['nama'];
        $validated['type'] = $validated['jenis'];
        $validated['stock'] = $validated['stok'];
        $validated['price_per_day'] = $validated['harga_per_hari'];

        Accessory::create($validated);
        return redirect()->route('admin.accessories.index')->with('status', 'Aksesoris dibuat');
    }

    public function edit(Accessory $accessory)
    {
        Gate::authorize('access-admin');
        return view('admin.accessories.edit', compact('accessory'));
    }

    public function update(Request $request, Accessory $accessory)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'nama' => ['required','string','max:255'],
            'jenis' => ['required','string','max:100'],
            'stok' => ['required','integer','min:0','max:1000'],
            'harga_per_hari' => ['required','numeric','min:0','max:999999'],
            'gambar' => ['nullable','image','mimes:jpeg,jpg,png,webp','max:1024','dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($accessory->gambar && Storage::disk('public')->exists($accessory->gambar)) {
                Storage::disk('public')->delete($accessory->gambar);
            }
            $path = $request->file('gambar')->store('images/accessories', 'public');
            $validated['gambar'] = $path;
        } else {
            unset($validated['gambar']);
        }

        // Kompatibilitas kolom lama
        $validated['name'] = $validated['nama'];
        $validated['type'] = $validated['jenis'];
        $validated['stock'] = $validated['stok'];
        $validated['price_per_day'] = $validated['harga_per_hari'];

        $accessory->update($validated);
        return redirect()->route('admin.accessories.index')->with('status', 'Aksesoris diperbarui');
    }

    public function destroy(Accessory $accessory)
    {
        Gate::authorize('access-admin');
        $hasActiveRentals = $accessory->rentalItems()
            ->whereHas('rental', function ($q) {
                $q->where('status', '!=', 'returned');
            })
            ->exists();
        if ($hasActiveRentals) {
            return redirect()->route('admin.accessories.index')->with('status', 'Aksesoris tidak bisa dihapus karena masih terkait transaksi yang belum dikembalikan');
        }
        $accessory->delete();
        return redirect()->route('admin.accessories.index')->with('status', 'Aksesoris dihapus');
    }
}
