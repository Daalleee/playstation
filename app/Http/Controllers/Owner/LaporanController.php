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

    public function pendapatan(Request $request)
    {
        Gate::authorize('access-pemilik');
        
        // Get date range from request or use defaults (last 7 days)
        $dari = $request->input('dari', now()->subDays(6)->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->format('Y-m-d'));
        
        // Validate and parse dates
        $start = \Carbon\Carbon::parse($dari)->startOfDay();
        $end = \Carbon\Carbon::parse($sampai)->endOfDay();
        
        // Chart: Revenue for selected date range
        $paymentData = \App\Models\Payment::whereBetween('paid_at', [$start, $end])
            ->selectRaw('DATE(paid_at) as payment_date, SUM(amount) as total_amount')
            ->groupBy('payment_date')
            ->pluck('total_amount', 'payment_date');

        $revLabels = [];
        $revData = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $revLabels[] = $d->format('d M');
            $revData[] = (int) ($paymentData[$key] ?? 0);
        }

        // Stats Summary
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $revenueTotal = \App\Models\Payment::sum('amount');
        $revenueToday = \App\Models\Payment::where('paid_at', '>=', $today)->sum('amount');
        $revenueMonth = \App\Models\Payment::where('paid_at', '>=', $monthStart)->sum('amount');
        
        // Revenue for selected period
        $revenueFiltered = array_sum($revData);

        $revenueStats = [
            'total' => $revenueTotal,
            'today' => $revenueToday,
            'month' => $revenueMonth,
            'filtered' => $revenueFiltered,
        ];

        // Detailed Payments List (filtered by date range)
        $revenueList = \App\Models\Payment::with(['rental.customer'])
            ->whereBetween('paid_at', [$start, $end])
            ->orderByDesc('paid_at')
            ->paginate(20);

        // Calculate period label
        $periodDays = round($start->diffInDays($end)) + 1;
        $periodLabel = $periodDays . ' Hari';
        if ($periodDays == 1) {
            $periodLabel = $start->format('d M Y');
        } elseif ($periodDays == 7) {
            $periodLabel = '7 Hari Terakhir';
        } elseif ($periodDays == 30) {
            $periodLabel = '30 Hari Terakhir';
        }

        return view('owner.laporan_pendapatan', compact(
            'revLabels', 
            'revData', 
            'revenueStats', 
            'revenueList',
            'dari',
            'sampai',
            'periodLabel'
        ));
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
