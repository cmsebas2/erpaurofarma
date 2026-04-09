<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStepIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_step_id',
        'formula_ingredient_id',
        'percentage_allocation'
    ];

    public function productStep()
    {
        return $this->belongsTo(ProductStep::class);
    }

    public function formulaIngredient()
    {
        return $this->belongsTo(FormulaIngredient::class);
    }
}
