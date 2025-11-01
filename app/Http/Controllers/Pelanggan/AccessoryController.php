<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-pelanggan');
        $query = Accessory::where('stok', '>', 0);

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', 'like', '%' . $request->jenis . '%');
        }

        // Search by name
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        $accessories = $query->latest()->paginate(12);
            
        return view('pelanggan.accessories.index', compact('accessories'));
    }
}
