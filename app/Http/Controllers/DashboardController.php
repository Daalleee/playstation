<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use App\Models\RentalItem;
use App\Models\Rental;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function admin()
    {
        Gate::authorize('access-admin');
        // Hitung statistik inventaris
        $unitAvailable = UnitPS::all()->sum(function ($u) { return ($u->stok ?? $u->stock ?? 0); });
        $unitRented = RentalItem::whereHas('rental', function ($q) { $q->where('status', 'active'); })
            ->where('rentable_type', UnitPS::class)
            ->sum('quantity');
        $unitDamaged = UnitPS::where('kondisi', 'rusak')->count();
        $unitTotal = $unitAvailable + $unitRented;

        $gameAvailable = Game::all()->sum(function ($g) { return ($g->stok ?? $g->stock ?? 0); });
        $gameRented = RentalItem::whereHas('rental', function ($q) { $q->where('status', 'active'); })
            ->where('rentable_type', Game::class)
            ->sum('quantity');
        $gameDamaged = Game::where('kondisi', 'rusak')->count();
        $gameTotal = $gameAvailable + $gameRented;

        $accAvailable = Accessory::all()->sum(function ($a) { return ($a->stok ?? $a->stock ?? 0); });
        $accRented = RentalItem::whereHas('rental', function ($q) { $q->where('status', 'active'); })
            ->where('rentable_type', Accessory::class)
            ->sum('quantity');
        $accDamaged = Accessory::where('kondisi', 'rusak')->count();
        $accTotal = $accAvailable + $accRented;

        $stats = [
            ['name' => 'Unit PS', 'total' => $unitTotal, 'available' => $unitAvailable, 'rented' => $unitRented, 'damaged' => $unitDamaged],
            ['name' => 'Game', 'total' => $gameTotal, 'available' => $gameAvailable, 'rented' => $gameRented, 'damaged' => $gameDamaged],
            ['name' => 'Aksesoris', 'total' => $accTotal, 'available' => $accAvailable, 'rented' => $accRented, 'damaged' => $accDamaged],
        ];

        return view('dashboards.admin', compact('stats'));
    }

    public function kasir()
    {
        Gate::authorize('access-kasir');
        $activeRentals = Rental::with(['customer','items.rentable'])
            ->whereIn('status', ['active','paid'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('dashboards.kasir', compact('activeRentals'));
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

    public function adminReport()
    {
        Gate::authorize('access-admin');
        // Ringkasan pendapatan
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();
        $revenueTotal = Payment::sum('amount');
        $revenueToday = Payment::where('paid_at', '>=', $today)->sum('amount');
        $revenueMonth = Payment::where('paid_at', '>=', $monthStart)->sum('amount');

        // Ringkasan transaksi
        $rentalsTotal = Rental::count();
        $rentalsActive = Rental::where('status', 'active')->count();
        $rentalsReturned = Rental::where('status', 'returned')->count();

        // Pembayaran terakhir
        $latestPayments = Payment::with('rental.customer')->orderByDesc('paid_at')->take(10)->get();

        return view('admin.laporan.index', compact(
            'revenueTotal', 'revenueToday', 'revenueMonth',
            'rentalsTotal', 'rentalsActive', 'rentalsReturned',
            'latestPayments'
        ));
    }
}


