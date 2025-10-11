<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Rental;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RentalExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-pemilik');
        $query = Rental::with(['customer', 'items', 'payments'])->orderByDesc('start_at');
        if ($request->filled('dari')) {
            $query->whereDate('start_at', '>=', $request->input('dari'));
        }
        if ($request->filled('sampai')) {
            $query->whereDate('start_at', '<=', $request->input('sampai'));
        }
        $rentals = $query->get();
        return view('owner.laporan', compact('rentals'));
    }

    public function export(Request $request)
    {
        Gate::authorize('access-pemilik');
        $format = $request->get('format', 'xlsx');
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');
        return Excel::download(new \App\Exports\RentalExport($dari, $sampai), 'laporan_transaksi.' . $format);
    }
}
