<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchPackagingWeightControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'weight',
        'controlled_at',
    ];

    protected $casts = [
        'controlled_at' => 'datetime',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }
}
