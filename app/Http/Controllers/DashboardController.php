<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;

class DashboardController extends Controller
{
    public function admin()
    {
        Gate::authorize('access-admin');
        return view('dashboards.admin');
    }

    public function kasir()
    {
        Gate::authorize('access-kasir');
        return view('dashboards.kasir');
    }

    public function pemilik()
    {
        Gate::authorize('access-pemilik');
        return view('dashboards.pemilik');
    }

    public function pelanggan()
    {
        Gate::authorize('access-pelanggan');
        $unitps = UnitPS::latest()->take(6)->get();
        $games = Game::latest()->take(6)->get();
        $accessories = Accessory::latest()->take(6)->get();
        return view('dashboards.pelanggan', compact('unitps', 'games', 'accessories'));
    }
}


