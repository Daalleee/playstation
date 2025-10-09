<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'type', 'item_id', 'name', 'price', 'price_type', 'quantity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
