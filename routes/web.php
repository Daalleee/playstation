<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\UnitPSController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Kasir\RentalController as KasirRentalController;
use App\Http\Controllers\Kasir\PaymentController as KasirPaymentController;
use App\Http\Controllers\Pelanggan\UnitPSController as PelangganUnitPSController;
use App\Http\Controllers\Pelanggan\GameController as PelangganGameController;
use App\Http\Controllers\Pelanggan\AccessoryController as PelangganAccessoryController;
use App\Http\Controllers\Pelanggan\ProfileController as PelangganProfileController;
use App\Http\Controllers\Pelanggan\CartController as PelangganCartController;
use App\Http\Controllers\Pelanggan\RentalController as PelangganRentalController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Owner\StatusProdukController;
use App\Http\Controllers\Owner\LaporanController;
use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Admin\ImpersonateController;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return match ($role) {
            'admin' => redirect()->route('dashboard.admin'),
            'kasir' => redirect()->route('dashboard.kasir'),
            'pemilik' => redirect()->route('dashboard.pemilik'),
            'pelanggan' => redirect()->route('dashboard.pelanggan'),
            default => redirect()->route('dashboard.pelanggan'),
        };
    }
    return view('auth.login');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware(['web', 'auth'])->group(function () {
    // Admin - Pelanggan
    Route::resource('admin/pelanggan', PelangganController::class)->parameters([
        'pelanggan' => 'pelanggan'
    ])->names('admin.pelanggan');

    // Admin - UnitPS
    Route::resource('admin/unitps', UnitPSController::class)->parameters([
        'unitps' => 'unitp'
    ])->names('admin.unitps');

    // Admin - Games
    Route::resource('admin/games', GameController::class)->names('admin.games');

    // Admin - Accessories
    Route::resource('admin/accessories', AccessoryController::class)->names('admin.accessories');

    // Admin - Staff (buat admin/kasir/pemilik)
    Route::get('admin/staff', [StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('admin/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
    Route::post('admin/staff', [StaffController::class, 'store'])->name('admin.staff.store');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/kasir', [DashboardController::class, 'kasir'])->name('dashboard.kasir');
    Route::get('/dashboard/pemilik', [DashboardController::class, 'pemilik'])->name('dashboard.pemilik');
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])->name('dashboard.pelanggan');

    // Logout (POST)
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

Route::middleware(['web', 'auth'])->group(function () {
    // Pelanggan - Profile
    Route::get('pelanggan/profile', [PelangganProfileController::class, 'show'])->name('pelanggan.profile.show');
    Route::get('pelanggan/profile/edit', [PelangganProfileController::class, 'edit'])->name('pelanggan.profile.edit');
    Route::put('pelanggan/profile', [PelangganProfileController::class, 'update'])->name('pelanggan.profile.update');

    // Pelanggan - View Catalog
    Route::get('pelanggan/unitps', [PelangganUnitPSController::class, 'index'])->name('pelanggan.unitps.index');
    Route::get('pelanggan/games', [PelangganGameController::class, 'index'])->name('pelanggan.games.index');
    Route::get('pelanggan/accessories', [PelangganAccessoryController::class, 'index'])->name('pelanggan.accessories.index');

    // Pelanggan - Cart
    Route::get('pelanggan/cart', [PelangganCartController::class, 'index'])->name('pelanggan.cart.index');
    Route::post('pelanggan/cart/add', [PelangganCartController::class, 'add'])->name('pelanggan.cart.add');
    Route::post('pelanggan/cart/update', [PelangganCartController::class, 'update'])->name('pelanggan.cart.update');
    Route::post('pelanggan/cart/remove', [PelangganCartController::class, 'remove'])->name('pelanggan.cart.remove');
    Route::post('pelanggan/cart/clear', [PelangganCartController::class, 'clear'])->name('pelanggan.cart.clear');

    // Pelanggan - Rentals
    Route::get('pelanggan/rentals', [PelangganRentalController::class, 'index'])->name('pelanggan.rentals.index');
    Route::get('pelanggan/rentals/create', [PelangganRentalController::class, 'create'])->name('pelanggan.rentals.create');
    Route::post('pelanggan/rentals', [PelangganRentalController::class, 'store'])->name('pelanggan.rentals.store');
    Route::get('pelanggan/rentals/{rental}', [PelangganRentalController::class, 'show'])->name('pelanggan.rentals.show');
});

Route::middleware(['web', 'auth', 'can:access-pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('status-produk', [StatusProdukController::class, 'index'])->name('status_produk');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});

Route::middleware(['web', 'auth', 'can:access-kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index'); // form cari
    Route::get('transaksi/{rental}', [TransaksiController::class, 'show'])->name('transaksi.show'); // detail
    Route::post('transaksi/{rental}/pengembalian', [TransaksiController::class, 'pengembalian'])->name('transaksi.pengembalian'); // konfirmasi
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('admin/impersonate/{user}', [ImpersonateController::class, 'impersonate'])->name('admin.impersonate');
    Route::post('admin/impersonate/leave', [ImpersonateController::class, 'leaveImpersonate'])->name('admin.impersonate.leave');
});
