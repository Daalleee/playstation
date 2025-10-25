<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\UnitPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UnitPSController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-pelanggan');
        $query = UnitPS::where('status', 'available')
            ->where('stok', '>', 0);

        // Filter by model (Tipe)
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        // Filter by brand (Platform)
        if ($request->filled('brand')) {
            $query->where('merek', $request->brand);
        }

        // Search by name
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->q . '%')
                    ->orWhere('name', 'like', '%' . $request->q . '%')
                    ->orWhere('model', 'like', '%' . $request->q . '%');
            });
        }

        // $units = $query->latest()->paginate(12);
        $units = UnitPS::where('stok', '>', 0)
            ->latest()
            ->paginate(12);

        return view('pelanggan.unitps.index', compact('units'));
    }
}
