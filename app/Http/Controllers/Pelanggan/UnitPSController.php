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
        $query = UnitPS::where('stock', '>', 0);

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Search by name
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('model', 'like', '%' . $request->q . '%');
            });
        }

        $units = $query->latest()->paginate(12);

        return view('pelanggan.unitps.index', compact('units'));
    }
}
