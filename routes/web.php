<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;
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
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProfileController;

// Midtrans webhook (must be outside auth middleware)
Route::post('midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
Route::get('midtrans/status/{orderId}', [MidtransController::class, 'checkStatus'])->name('midtrans.status');

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
    return view('landing');
});

// Public landing page (direct link)
// Public landing page (direct link)
Route::view('/landing', 'landing')->name('landing');

// Guest Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/contact', 'pages.contact')->name('contact');

// Serve files from the public disk without requiring the /public/storage symlink
Route::get('/media/{path}', function (string $path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media');

Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Password Reset
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Alias for default auth middleware redirect (expects route name 'login')
Route::get('/auth', function () {
    return redirect()->route('login.show');
})->name('login');

Route::middleware(['web', 'auth'])->group(function () {
    // Unified Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

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

    // Admin - Staff management (fallback and specific routes)
    Route::get('admin/staff', [StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('admin/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
    
    // Admin - Kelola Admin
    Route::get('admin/admin', [StaffController::class, 'adminIndex'])->name('admin.admin.index');
    Route::get('admin/admin/create', [StaffController::class, 'adminCreate'])->name('admin.admin.create');
    Route::post('admin/admin', [StaffController::class, 'store'])->name('admin.admin.store');
    Route::get('admin/admin/{user}/edit', [StaffController::class, 'edit'])->name('admin.admin.edit');
    Route::put('admin/admin/{user}', [StaffController::class, 'update'])->name('admin.admin.update');
    Route::delete('admin/admin/{user}', [StaffController::class, 'destroy'])->name('admin.admin.destroy');
    
    // Admin - Kelola Pemilik
    Route::get('admin/pemilik', [StaffController::class, 'pemilikIndex'])->name('admin.pemilik.index');
    Route::get('admin/pemilik/create', [StaffController::class, 'pemilikCreate'])->name('admin.pemilik.create');
    Route::post('admin/pemilik', [StaffController::class, 'store'])->name('admin.pemilik.store');
    Route::get('admin/pemilik/{user}/edit', [StaffController::class, 'edit'])->name('admin.pemilik.edit');
    Route::put('admin/pemilik/{user}', [StaffController::class, 'update'])->name('admin.pemilik.update');
    Route::delete('admin/pemilik/{user}', [StaffController::class, 'destroy'])->name('admin.pemilik.destroy');
    
    // Admin - Kelola Kasir
    Route::get('admin/kasir', [StaffController::class, 'kasirIndex'])->name('admin.kasir.index');
    Route::get('admin/kasir/create', [StaffController::class, 'kasirCreate'])->name('admin.kasir.create');
    Route::post('admin/kasir', [StaffController::class, 'store'])->name('admin.kasir.store');
    Route::get('admin/kasir/{user}/edit', [StaffController::class, 'edit'])->name('admin.kasir.edit');
    Route::put('admin/kasir/{user}', [StaffController::class, 'update'])->name('admin.kasir.update');
    Route::delete('admin/kasir/{user}', [StaffController::class, 'destroy'])->name('admin.kasir.destroy');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/kasir', [DashboardController::class, 'kasir'])->name('dashboard.kasir');
    Route::get('/dashboard/pemilik', [DashboardController::class, 'pemilik'])->name('dashboard.pemilik');
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])->name('dashboard.pelanggan');
    Route::get('admin/laporan', [DashboardController::class, 'adminReport'])->name('admin.laporan');

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
    Route::get('pelanggan/unitps', [DashboardController::class, 'unitpsLanding'])->name('pelanggan.unitps.index');
    Route::get('pelanggan/unitps/list', [PelangganUnitPSController::class, 'index'])->name('pelanggan.unitps.list');
    Route::get('pelanggan/games', [DashboardController::class, 'gameLanding'])->name('pelanggan.games.index');
    Route::get('pelanggan/games/list', [PelangganGameController::class, 'index'])->name('pelanggan.games.list');
    Route::get('pelanggan/accessories', [DashboardController::class, 'accessoryLanding'])->name('pelanggan.accessories.index');
    Route::get('pelanggan/accessories/list', [PelangganAccessoryController::class, 'index'])->name('pelanggan.accessories.list');

    // Pelanggan - Cart
    Route::get('pelanggan/cart', [PelangganCartController::class, 'index'])->name('pelanggan.cart.index');
    Route::post('pelanggan/cart/add', [PelangganCartController::class, 'add'])->name('pelanggan.cart.add');
    Route::post('pelanggan/cart/update', [PelangganCartController::class, 'update'])->name('pelanggan.cart.update');
    Route::post('pelanggan/cart/remove', [PelangganCartController::class, 'remove'])->name('pelanggan.cart.remove');
    Route::post('pelanggan/cart/clear', [PelangganCartController::class, 'clear'])->name('pelanggan.cart.clear');

    // Pelanggan - Rentals
    Route::get('pelanggan/rentals', [PelangganRentalController::class, 'index'])->name('pelanggan.rentals.index');
    Route::get('pelanggan/rentals/create', [PelangganRentalController::class, 'create'])->name('pelanggan.rentals.create');
    Route::post('pelanggan/rentals', [PelangganRentalController::class, 'store'])
        ->middleware(['throttle:3,1', 'ensure.profile.complete']) // Max 3 requests per minute + check profile
        ->name('pelanggan.rentals.store');
    Route::get('pelanggan/rentals/{rental}', [PelangganRentalController::class, 'show'])->name('pelanggan.rentals.show');
    Route::post('pelanggan/rentals/{rental}/return', [PelangganRentalController::class, 'returnRental'])->name('pelanggan.rentals.return');
});

Route::middleware(['web', 'auth', 'can:access-pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('status-produk', [StatusProdukController::class, 'index'])->name('status_produk');
    Route::get('laporan-transaksi', [LaporanController::class, 'index'])->name('laporan_transaksi');
    Route::get('laporan-pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan_pendapatan');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});

Route::middleware(['web', 'auth', 'can:access-kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index'); // form cari
    Route::get('transaksi/{rental}', [TransaksiController::class, 'show'])->name('transaksi.show'); // detail
    Route::post('transaksi/{rental}/pengembalian', [TransaksiController::class, 'pengembalian'])->name('transaksi.pengembalian'); // konfirmasi
    Route::post('transaksi/{rental}/aktifkan', [TransaksiController::class, 'aktifkan'])->name('transaksi.aktifkan'); // aktifkan sewa setelah dibayar
    
    // Kasir - Rentals Management
    Route::get('rentals', [KasirRentalController::class, 'index'])->name('rentals.index');
    Route::get('rentals/create', [KasirRentalController::class, 'create'])->name('rentals.create');
    Route::post('rentals', [KasirRentalController::class, 'store'])->name('rentals.store');
    Route::get('rentals/{rental}', [KasirRentalController::class, 'show'])->name('rentals.show');
    Route::post('rentals/{rental}/return', [KasirRentalController::class, 'return'])->name('rentals.return');
    Route::post('rentals/{rental}/confirm-return', [KasirRentalController::class, 'confirmReturn'])->name('rentals.confirm-return');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('admin/impersonate/{user}', [ImpersonateController::class, 'impersonate'])->name('admin.impersonate');
    Route::post('admin/impersonate/leave', [ImpersonateController::class, 'leaveImpersonate'])->name('admin.impersonate.leave');
});
