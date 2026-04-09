<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpPresentation extends Model
{
    protected $fillable = [
        'production_order_id',
        'presentation_id',
        'units_to_produce',
        'total_kg',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function presentation()
    {
        return $this->belongsTo(ProductPresentation::class, 'presentation_id');
    }
}
