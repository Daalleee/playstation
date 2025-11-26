<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'type', 'item_id', 'name', 'price', 'price_type', 'quantity'
    ];

    protected $appends = ['item', 'subtotal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the actual item (UnitPS, Game, or Accessory)
     */
    public function getItemAttribute()
    {
        $modelClass = match($this->type) {
            'unitps' => UnitPS::class,
            'game' => Game::class,
            'accessory' => Accessory::class,
            default => null,
        };

        if (!$modelClass) {
            return null;
        }

        return $modelClass::find($this->item_id);
    }

    /**
     * Get subtotal for this cart item
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get item name (fallback to stored name if item deleted)
     */
    public function getItemNameAttribute()
    {
        if ($this->item) {
            return $this->item->nama ?? $this->item->judul ?? $this->item->name ?? $this->name;
        }
        return $this->name;
    }

    /**
     * Get item image (fallback to placeholder if item deleted)
     */
    public function getItemImageAttribute()
    {
        if (!$this->item) {
            return 'https://placehold.co/100x100/49497A/FFFFFF?text=No+Image';
        }

        // UnitPS uses 'foto', others use 'gambar'
        $imageField = $this->type === 'unitps' ? 'foto' : 'gambar';
        $imagePath = $this->item->$imageField;

        if ($imagePath) {
            // Check if it's a full URL (e.g. from seeder)
            if (str_starts_with($imagePath, 'http')) {
                return $imagePath;
            }
            return asset('storage/' . $imagePath);
        }
        
        return 'https://placehold.co/100x100/49497A/FFFFFF?text=No+Image';
    }

    /**
     * Check if item still has enough stock
     */
    public function hasEnoughStock()
    {
        if (!$this->item) {
            return false;
        }
        
        // Unit PS uses 'stock', Games and Accessories use 'stok'
        $stockField = $this->type === 'unitps' ? 'stock' : 'stok';
        $availableStock = $this->item->$stockField ?? 0;
        
        return $availableStock >= $this->quantity;
    }

    /**
     * Get available stock for this item
     */
    public function getAvailableStock()
    {
        if (!$this->item) {
            return 0;
        }
        
        // Unit PS uses 'stock', Games and Accessories use 'stok'
        $stockField = $this->type === 'unitps' ? 'stock' : 'stok';
        return $this->item->$stockField ?? 0;
    }
}
