<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'step_number',
        'type',
        'description',
        'theoretical_time_minutes',
        'target_rpm'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ProductStepIngredient::class)->with('formulaIngredient');
    }
}
