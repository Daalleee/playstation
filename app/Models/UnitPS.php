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

    public function instances()
    {
        return $this->hasMany(UnitPSInstance::class, 'unit_ps_id');
    }

    public function getAvailableInstancesCountAttribute()
    {
        return $this->instances()->where('status', 'available')->count();
    }

    public static function getFixedMasterUnits()
    {
        return [
            [
                'name' => 'PlayStation 2',
                'model' => 'PS2',
                'brand' => 'Sony',
                'default_price' => 15000,
            ],
            [
                'name' => 'PlayStation 3',
                'model' => 'PS3',
                'brand' => 'Sony',
                'default_price' => 20000,
            ],
            [
                'name' => 'PlayStation 4',
                'model' => 'PS4',
                'brand' => 'Sony',
                'default_price' => 30000,
            ],
            [
                'name' => 'PlayStation 5',
                'model' => 'PS5',
                'brand' => 'Sony',
                'default_price' => 50000,
            ],
        ];
    }

    public static function isValidFixedMasterModel($model)
    {
        $fixedUnits = self::getFixedMasterUnits();
        $fixedModels = array_column($fixedUnits, 'model');

        // Allow exact matches or variations (like "PS5 Hitam", "PS4 Putih", etc.)
        foreach ($fixedModels as $fixedModel) {
            if (stripos($model, $fixedModel) === 0) { // Model starts with the fixed model
                return true;
            }
        }

        return false;
    }

    public static function getBaseModel($model)
    {
        $fixedUnits = self::getFixedMasterUnits();
        $fixedModels = array_column($fixedUnits, 'model');

        foreach ($fixedModels as $fixedModel) {
            if (stripos($model, $fixedModel) === 0) {
                return $fixedModel;
            }
        }

        return $model;
    }
}
