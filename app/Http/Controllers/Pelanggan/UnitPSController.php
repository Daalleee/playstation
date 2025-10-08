<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\UnitPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UnitPSController extends Controller
{
    public function index()
    {
        Gate::authorize('access-pelanggan');
        
        $units = UnitPS::where('status', 'available')
            ->where('stok', '>', 0)
            ->latest()
            ->paginate(12);
            
        return view('pelanggan.unitps.index', compact('units'));
    }
}
