<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'name',
        'jenis',
        'type',
        'stok',
        'stock',
        'harga_per_hari',
        'price_per_day',
        'gambar',
        'kondisi',
    ];

    public function rentalItems(): MorphMany
    {
        return $this->morphMany(RentalItem::class, 'rentable');
    }
}


