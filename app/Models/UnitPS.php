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
        'id',
        'name',
        'brand',
        'model', 
        'serial_number',
        'price_per_hour',
        'stock',
        'nama',
        'merek',
        'nomor_seri',
        'harga_per_jam',
        'stok',
        'foto',
        'kondisi',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'harga_per_jam' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'stock' => 'integer',
        'stok' => 'integer',
    ];

    // Accessor untuk field Indonesia agar kompatibel dengan view
    public function getNamaAttribute()
    {
        return $this->attributes['nama'] ?? $this->attributes['name'] ?? '';
    }

    public function getMerekAttribute()
    {
        return $this->attributes['merek'] ?? $this->attributes['brand'] ?? '';
    }

    public function getNomorSeriAttribute()
    {
        return $this->attributes['nomor_seri'] ?? $this->attributes['serial_number'] ?? '';
    }

    public function getHargaPerJamAttribute()
    {
        return $this->attributes['harga_per_jam'] ?? $this->attributes['price_per_hour'] ?? 0;
    }

    public function getStokAttribute()
    {
        return $this->attributes['stok'] ?? $this->attributes['stock'] ?? 0;
    }

    public function rentalItems(): MorphMany
    {
        return $this->morphMany(RentalItem::class, 'rentable');
    }
}


