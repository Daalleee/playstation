<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        return view('dashboards.pelanggan');
    }
}


