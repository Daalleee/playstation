<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Rental;
use App\Models\RentalItem;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-kasir');
        $rentals = Rental::with('customer')->orderByDesc('start_at')->paginate(10); // tampilkan semua
        $rental = null;
        if ($request->filled('rental_kode')) {
            $kode = $request->rental_kode;
            $rental = Rental::where('kode', $kode)->with('customer','items.rentable')->first();
            if (!$rental) {
                return view('kasir.transaksi.index', compact('rentals'))
                    ->with('status', 'Kode transaksi tidak ditemukan.');
            }
        }
        return view('kasir.transaksi.index', compact('rental', 'rentals'));
    }

    public function show(Rental $rental)
    {
        Gate::authorize('access-kasir');
        $rental->load('items.rentable');
        return view('kasir.transaksi.show', compact('rental'));
    }

    public function aktifkan(Rental $rental)
    {
        Gate::authorize('access-kasir');
        
        // Validasi status transition yang valid
        if (!in_array($rental->status, ['paid', 'pending'])) {
            return back()->withErrors(['error' => 'Transaksi hanya bisa diaktifkan dari status paid/pending. Status saat ini: ' . $rental->status]);
        }
        
        // Cek apakah sudah pernah aktif/returned
        if (in_array($rental->status, ['active', 'returned', 'cancelled'])) {
            return back()->withErrors(['error' => 'Transaksi sudah pernah diaktifkan atau sudah selesai.']);
        }
        
        $rental->status = 'active';
        $rental->save();
        return back()->with('status', 'Transaksi sewa sudah diaktifkan, barang sudah diberikan ke pelanggan.');
    }

    public function pengembalian(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['required', 'exists:rental_items,id'],
            'kondisi' => ['required', 'array'],
            'kondisi.*' => ['required', 'string', 'max:255'],
        ]);
        
        \DB::transaction(function() use ($rental, $validated) {
            foreach ($rental->items as $item) {
                $itemId = $item->id;
                if (isset($validated['items'][$itemId])) {
                    // Update stok barang dengan pessimistic locking
                    $rentable = $item->rentable()->lockForUpdate()->first();
                    if ($rentable) {
                        $rentable->stok = ($rentable->stok ?? 0) + $item->quantity;
                        $rentable->save();
                    }
                    
                    // Update kondisi jika perlu
                    if (isset($validated['kondisi'][$itemId])) {
                        $item->kondisi_kembali = $validated['kondisi'][$itemId];
                        $item->save();
                    }
                }
            }
            
            $rental->status = 'returned';
            $rental->returned_at = now();
            $rental->save();
        });
        
        return redirect()->route('kasir.transaksi.index')->with('status', 'Pengembalian berhasil dikonfirmasi.');
    }
}
