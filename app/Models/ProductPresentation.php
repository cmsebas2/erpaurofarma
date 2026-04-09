<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPresentation extends Model
{
    protected $fillable = [
        'product_id', 'presentation_code', 'name'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materials()
    {
        return $this->hasMany(FormulaIngredient::class, 'presentation_id');
    }

    public function packaging_materials()
    {
        return $this->hasMany(FormulaIngredient::class, 'presentation_id');
    }
}
