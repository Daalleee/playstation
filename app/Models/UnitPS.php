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
        'merek',
        'model',
        'nomor_seri',
        'harga_per_jam',
        'stok',
        'status',
        'foto',
        'kondisi',
    ];

    public function rentalItems(): MorphMany
    {
        return $this->morphMany(RentalItem::class, 'rentable');
    }
}


