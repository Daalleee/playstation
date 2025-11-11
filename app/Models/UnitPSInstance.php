<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitPSInstance extends Model
{
    protected $table = 'unit_ps_instances';  // explicitly specify the table name

    protected $fillable = [
        'unit_ps_id',
        'serial_number',
        'status',
        'condition',
    ];

    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function unitPS()
    {
        return $this->belongsTo(UnitPS::class, 'unit_ps_id');
    }
}
