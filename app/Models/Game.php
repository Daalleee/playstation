<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'platform',
        'genre',
        'stok',
        'harga_per_hari',
        'gambar',
        'kondisi',
    ];

    public function rentalItems(): MorphMany
    {
        return $this->morphMany(RentalItem::class, 'rentable');
    }
}


