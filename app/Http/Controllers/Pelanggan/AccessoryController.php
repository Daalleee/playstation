<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessoryController extends Controller
{
    public function index()
    {
        Gate::authorize('access-pelanggan');
        
        $accessories = Accessory::where('stok', '>', 0)
            ->latest()
            ->paginate(12);
            
        return view('pelanggan.accessories.index', compact('accessories'));
    }
}
