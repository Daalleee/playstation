<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\UnitPS;
use App\Models\Game;
use App\Models\Accessory;
use App\Models\RentalItem;
use App\Models\Rental;

class StatusProdukController extends Controller
{
    public function index()
    {
        Gate::authorize('access-pemilik');
        $unitps = UnitPS::all();
        $games = Game::all();
        $accessories = Accessory::all();
        // Ambil id rental yang statusnya ongoing/active
        $activeRentalIds = Rental::whereIn('status', ['ongoing', 'active'])->pluck('id');
        // Ambil id produk yang sedang disewa
        $unitpsRented = RentalItem::where('rentable_type', UnitPS::class)->whereIn('rental_id', $activeRentalIds)->pluck('rentable_id')->toArray();
        $gamesRented = RentalItem::where('rentable_type', Game::class)->whereIn('rental_id', $activeRentalIds)->pluck('rentable_id')->toArray();
        $accessoriesRented = RentalItem::where('rentable_type', Accessory::class)->whereIn('rental_id', $activeRentalIds)->pluck('rentable_id')->toArray();
        return view('owner.status_produk', compact('unitps', 'games', 'accessories', 'unitpsRented', 'gamesRented', 'accessoriesRented'));
    }
}
