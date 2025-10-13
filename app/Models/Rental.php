<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'handled_by',
        'start_at',
        'due_at',
        'returned_at',
        'status',
        'subtotal',
        'discount',
        'total',
        'paid',
        'notes',
        'kode', // kode transaksi unik
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Generate kode transaksi unik 4 karakter (AA01, AB12, ...)
     */
    public static function generateKodeUnik(): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $maxTry = 100;
        for ($i = 0; $i < $maxTry; $i++) {
            $huruf1 = $alphabet[rand(0,25)];
            $huruf2 = $alphabet[rand(0,25)];
            $angka = str_pad(strval(rand(1,99)), 2, '0', STR_PAD_LEFT);
            $kode = $huruf1.$huruf2.$angka;
            if (!self::where('kode', $kode)->exists()) {
                return $kode;
            }
        }
        // fallback kode unik panjang jika semua terpakai
        return strtoupper(substr(uniqid(), -4));
    }
}


