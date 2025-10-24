<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RentalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'rentable_type',
        'rentable_id',
        'quantity',
        'price',
        'total',
        'kondisi_kembali',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function rentable(): MorphTo
    {
        return $this->morphTo();
    }
}


