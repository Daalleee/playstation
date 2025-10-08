<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GameController extends Controller
{
    public function index()
    {
        Gate::authorize('access-pelanggan');
        
        $games = Game::where('stok', '>', 0)
            ->latest()
            ->paginate(12);
            
        return view('pelanggan.games.index', compact('games'));
    }
}
