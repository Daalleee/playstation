<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UnitPSController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');
        $units = UnitPS::latest()->paginate(10);
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
            'nama' => ['required','string','max:255'],
            'merek' => ['required','string','max:255'],
            'model' => ['required','string','max:100'],
            'nomor_seri' => ['required','string','max:255','unique:unit_ps,nomor_seri'],
            'harga_per_jam' => ['required','numeric','min:0'],
            'stok' => ['required','integer','min:0'],
            'status' => ['required','in:available,rented,maintenance'],
            'foto' => ['nullable','string','max:255'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

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
            'nama' => ['required','string','max:255'],
            'merek' => ['required','string','max:255'],
            'model' => ['required','string','max:100'],
            'nomor_seri' => ['required','string','max:255','unique:unit_ps,nomor_seri,'.$unitp->id],
            'harga_per_jam' => ['required','numeric','min:0'],
            'stok' => ['required','integer','min:0'],
            'status' => ['required','in:available,rented,maintenance'],
            'foto' => ['nullable','string','max:255'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

        $unitp->update($validated);
        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS diperbarui');
    }

    public function destroy(UnitPS $unitp)
    {
        Gate::authorize('access-admin');
        $unitp->delete();
        return redirect()->route('admin.unitps.index')->with('status', 'Unit PS dihapus');
    }
}


