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
use App\Http\Controllers\Admin\StaffController;

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
    return view('welcome');
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
    // Kasir - Rentals
    Route::get('kasir/rentals', [KasirRentalController::class, 'index'])->name('kasir.rentals.index');
    Route::get('kasir/rentals/create', [KasirRentalController::class, 'create'])->name('kasir.rentals.create');
    Route::post('kasir/rentals', [KasirRentalController::class, 'store'])->name('kasir.rentals.store');
    Route::get('kasir/rentals/{rental}', [KasirRentalController::class, 'show'])->name('kasir.rentals.show');
    Route::post('kasir/rentals/{rental}/return', [KasirRentalController::class, 'return'])->name('kasir.rentals.return');

    // Kasir - Payments
    Route::post('kasir/rentals/{rental}/payments', [KasirPaymentController::class, 'store'])->name('kasir.rentals.payments.store');
});
