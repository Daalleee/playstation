<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-pemilik');
        // Untuk awal, tampilkan view kosong, nanti bisa filter tanggal
        return view('owner.laporan');
    }
}
