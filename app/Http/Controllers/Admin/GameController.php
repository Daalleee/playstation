<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        Gate::authorize('access-admin');
        $games = Game::select('id', 'judul', 'platform', 'genre', 'stok', 'harga_per_hari', 'gambar', 'kondisi')
            ->latest()
            ->paginate(10);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        Gate::authorize('access-admin');
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'judul' => ['required','string','max:255'],
            'platform' => ['required','string','max:50'],
            'genre' => ['nullable','string','max:100'],
            'stok' => ['required','integer','min:0','max:1000'],
            'harga_per_hari' => ['required','numeric','min:0','max:999999'],
            'gambar' => ['nullable','image','mimes:jpeg,jpg,png,webp','max:1024','dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images/games', 'public');
            $validated['gambar'] = $path;
        } else {
            unset($validated['gambar']);
        }

        // Map ke kolom lama agar kompatibel dengan skema awal
        $validated['title'] = $validated['judul'];
        $validated['stock'] = $validated['stok'];
        $validated['price_per_day'] = $validated['harga_per_hari'];

        Game::create($validated);
        return redirect()->route('admin.games.index')->with('status', 'Game dibuat');
    }

    public function edit(Game $game)
    {
        Gate::authorize('access-admin');
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        Gate::authorize('access-admin');
        $validated = $request->validate([
            'judul' => ['required','string','max:255'],
            'platform' => ['required','string','max:50'],
            'genre' => ['nullable','string','max:100'],
            'stok' => ['required','integer','min:0','max:1000'],
            'harga_per_hari' => ['required','numeric','min:0','max:999999'],
            'gambar' => ['nullable','image','mimes:jpeg,jpg,png,webp','max:1024','dimensions:max_width=2000,max_height=2000'],
            'kondisi' => ['nullable','string','max:255'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($game->gambar && Storage::disk('public')->exists($game->gambar)) {
                Storage::disk('public')->delete($game->gambar);
            }
            $path = $request->file('gambar')->store('images/games', 'public');
            $validated['gambar'] = $path;
        } else {
            unset($validated['gambar']);
        }

        // Map ke kolom lama agar kompatibel dengan skema awal
        $validated['title'] = $validated['judul'];
        $validated['stock'] = $validated['stok'];
        $validated['price_per_day'] = $validated['harga_per_hari'];

        $game->update($validated);
        return redirect()->route('admin.games.index')->with('status', 'Game diperbarui');
    }

    public function destroy(Game $game)
    {
        Gate::authorize('access-admin');
        $hasActiveRentals = $game->rentalItems()
            ->whereHas('rental', function ($q) {
                $q->where('status', '!=', 'returned');
            })
            ->exists();
        if ($hasActiveRentals) {
            return redirect()->route('admin.games.index')->with('status', 'Game tidak bisa dihapus karena masih terkait transaksi yang belum dikembalikan');
        }
        $game->delete();
        return redirect()->route('admin.games.index')->with('status', 'Game dihapus');
    }
}
