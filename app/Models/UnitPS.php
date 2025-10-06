<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class UnitPS extends Model
{
    use HasFactory;

    protected $table = 'unit_ps';

    protected $fillable = [
        'nama',
        'name',
        'merek',
        'brand',
        'model',
        'nomor_seri',
        'serial_number',
        'harga_per_jam',
        'price_per_hour',
        'stok',
        'stock',
        'status',
        'foto',
        'kondisi',
    ];

    public function rentalItems(): MorphMany
    {
        return $this->morphMany(RentalItem::class, 'rentable');
    }
}


