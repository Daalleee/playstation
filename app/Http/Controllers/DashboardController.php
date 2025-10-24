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
<<<<<<< HEAD
        // Hitung statistik inventaris dengan efficient aggregate queries
        $unitAvailable = UnitPS::sum('stok');
        $unitRented = RentalItem::whereHas('rental', function ($q) { 
                $q->whereIn('status', ['active', 'ongoing']); 
            })
=======
        
        // Gunakan eager loading dan aggregasi untuk meningkatkan performa
        $unitPSData = UnitPS::selectRaw('*, COALESCE(stok, stock, 0) as total_stok')->get();
        $gameData = Game::selectRaw('*, COALESCE(stok, stock, 0) as total_stok')->get();
        $accessoryData = Accessory::selectRaw('*, COALESCE(stok, stock, 0) as total_stok')->get();
        
        $unitAvailable = $unitPSData->sum('total_stok');
        $unitRented = RentalItem::whereHas('rental', function ($q) { $q->where('status', 'active'); })
>>>>>>> cd6d258 (Optimalkan perhitungan statistik inventaris dan tingkatkan pengambilan data untuk dasbor admin)
            ->where('rentable_type', UnitPS::class)
            ->sum('quantity');
        $unitDamaged = UnitPS::where('kondisi', 'rusak')->count();
        $unitTotal = $unitAvailable + $unitRented;

<<<<<<< HEAD
        $gameAvailable = Game::sum('stok');
        $gameRented = RentalItem::whereHas('rental', function ($q) { 
                $q->whereIn('status', ['active', 'ongoing']); 
            })
=======
        $gameAvailable = $gameData->sum('total_stok');
        $gameRented = RentalItem::whereHas('rental', function ($q) { $q->where('status', 'active'); })
>>>>>>> cd6d258 (Optimalkan perhitungan statistik inventaris dan tingkatkan pengambilan data untuk dasbor admin)
            ->where('rentable_type', Game::class)
            ->sum('quantity');
        $gameDamaged = Game::where('kondisi', 'rusak')->count();
        $gameTotal = $gameAvailable + $gameRented;

<<<<<<< HEAD
        $accAvailable = Accessory::sum('stok');
        $accRented = RentalItem::whereHas('rental', function ($q) { 
                $q->whereIn('status', ['active', 'ongoing']); 
            })
=======
        $accAvailable = $accessoryData->sum('total_stok');
        $accRented = RentalItem::whereHas('rental', function ($q) { $q->where('status', 'active'); })
>>>>>>> cd6d258 (Optimalkan perhitungan statistik inventaris dan tingkatkan pengambilan data untuk dasbor admin)
            ->where('rentable_type', Accessory::class)
            ->sum('quantity');
        $accDamaged = Accessory::where('kondisi', 'rusak')->count();
        $accTotal = $accAvailable + $accRented;

        // Ambil rental yang aktif untuk menghitung jumlah sewa per item
        $activeRentalItems = RentalItem::whereHas('rental', function ($q) { $q->where('status', 'active'); })
            ->whereIn('rentable_type', [UnitPS::class, Game::class, Accessory::class])
            ->selectRaw('rentable_type, rentable_id, SUM(quantity) as total_rented')
            ->groupBy('rentable_type', 'rentable_id')
            ->get()
            ->keyBy(function($item) {
                return $item->rentable_type . '_' . $item->rentable_id;
            });

        // Optimasi data detail UnitPS
        $unitps = $unitPSData->map(function($unit) use ($activeRentalItems) {
            $key = UnitPS::class . '_' . $unit->id;
            $rentedCount = $activeRentalItems->has($key) ? $activeRentalItems->get($key)->total_rented : 0;
            
            return [
                'nama' => $unit->nama,
                'model' => $unit->model,
                'merek' => $unit->merek,
                'stok' => $unit->total_stok,
                'kondisi_baik' => $unit->total_stok, // Equals total stock since admin adds items as baik
                'kondisi_buruk' => 0, // Default to 0 since admin adds items as baik
                'disewa' => $rentedCount,
                'tersedia' => $unit->total_stok - $rentedCount,
                'nomor_seri' => $unit->nomor_seri ?? '-' // Tambahkan nomor seri
            ];
        });
        
        // Optimasi data detail Games
        $games = $gameData->map(function($game) use ($activeRentalItems) {
            $key = Game::class . '_' . $game->id;
            $rentedCount = $activeRentalItems->has($key) ? $activeRentalItems->get($key)->total_rented : 0;
            
            return [
                'nama' => $game->judul,
                'model' => $game->platform,
                'merek' => $game->genre,
                'stok' => $game->total_stok,
                'kondisi_baik' => $game->total_stok, // Equals total stock since admin adds items as baik
                'kondisi_buruk' => 0, // Default to 0 since admin adds items as baik
                'disewa' => $rentedCount,
                'tersedia' => $game->total_stok - $rentedCount
            ];
        });
        
        // Optimasi data detail Aksesoris
        $accessories = $accessoryData->map(function($acc) use ($activeRentalItems) {
            $key = Accessory::class . '_' . $acc->id;
            $rentedCount = $activeRentalItems->has($key) ? $activeRentalItems->get($key)->total_rented : 0;
            
            return [
                'nama' => $acc->nama,
                'model' => $acc->jenis,
                'merek' => $acc->kondisi ?? 'baik',
                'stok' => $acc->total_stok,
                'kondisi_baik' => $acc->total_stok, // Equals total stock since admin adds items as baik
                'kondisi_buruk' => 0, // Default to 0 since admin adds items as baik
                'disewa' => $rentedCount,
                'tersedia' => $acc->total_stok - $rentedCount
            ];
        });
        
        $stats = [
            ['name' => 'Unit PS', 'total' => $unitTotal, 'available' => $unitAvailable, 'rented' => $unitRented, 'damaged' => $unitDamaged],
            ['name' => 'Game', 'total' => $gameTotal, 'available' => $gameAvailable, 'rented' => $gameRented, 'damaged' => $gameDamaged],
            ['name' => 'Aksesoris', 'total' => $accTotal, 'available' => $accAvailable, 'rented' => $accRented, 'damaged' => $accDamaged],
        ];

        return view('dashboards.admin', compact('stats', 'unitps', 'games', 'accessories'));
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
        // KPI: Unit tersedia dan total transaksi hari ini
        $availableUnits = UnitPS::count();
        $todaysTransactions = Rental::whereDate('created_at', now()->toDateString())->count();

        // Tabel: Transaksi terbaru
        $recentTransactions = Rental::with(['customer'])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Chart: Pendapatan 7 hari terakhir - optimized
        $start = now()->copy()->subDays(6)->startOfDay();
        $end = now()->endOfDay();
        $paymentData = Payment::whereBetween('paid_at', [$start, $end])
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

        return view('dashboards.pemilik', compact(
            'availableUnits', 'todaysTransactions', 'recentTransactions', 'revLabels', 'revData'
        ));
    }

    public function pelanggan()
    {
        Gate::authorize('access-pelanggan');
        $unitps = UnitPS::orderByDesc('id')->limit(6)->get();
        $games = Game::orderByDesc('id')->limit(6)->get();
        $accessories = Accessory::orderByDesc('id')->limit(6)->get();
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

        // Pembayaran terakhir - gunakan eager loading terbatas
        $latestPayments = Payment::with(['rental' => function($query) {
                $query->with('customer');
            }])
            ->orderByDesc('paid_at')
            ->limit(10)
            ->get();

        return view('admin.laporan.index', compact(
            'revenueTotal', 'revenueToday', 'revenueMonth',
            'rentalsTotal', 'rentalsActive', 'rentalsReturned',
            'latestPayments'
        ));
    }
}


