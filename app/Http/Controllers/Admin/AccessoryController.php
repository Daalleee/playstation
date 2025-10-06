<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessoryController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');
        $accessories = Accessory::latest()->paginate(10);
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
            'stok' => ['required','integer','min:0'],
            'harga_per_hari' => ['required','numeric','min:0'],
            'gambar' => ['nullable','string','max:255'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

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
            'stok' => ['required','integer','min:0'],
            'harga_per_hari' => ['required','numeric','min:0'],
            'gambar' => ['nullable','string','max:255'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

        $accessory->update($validated);
        return redirect()->route('admin.accessories.index')->with('status', 'Aksesoris diperbarui');
    }

    public function destroy(Accessory $accessory)
    {
        Gate::authorize('access-admin');
        $accessory->delete();
        return redirect()->route('admin.accessories.index')->with('status', 'Aksesoris dihapus');
    }
}


