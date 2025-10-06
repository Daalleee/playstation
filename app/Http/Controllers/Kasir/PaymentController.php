<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function store(Request $request, Rental $rental)
    {
        Gate::authorize('access-kasir');
        $validated = $request->validate([
            'method' => ['required', 'in:cash,transfer,ewallet'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reference' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['nullable', 'date'],
        ]);

        $payment = Payment::create([
            'rental_id' => $rental->id,
            'method' => $validated['method'],
            'amount' => $validated['amount'],
            'reference' => $validated['reference'] ?? null,
            'paid_at' => $validated['paid_at'] ?? now(),
        ]);

        $rental->paid = ($rental->paid ?? 0) + $payment->amount;
        $rental->save();

        return back()->with('status', 'Pembayaran dicatat');
    }
}


