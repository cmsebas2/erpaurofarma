<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanStepIngredient extends Model
{
    protected $fillable = [
        'plan_step_id',
        'formula_ingredient_id',
        'unit',
        'theoretical_quantity',
        'percentage_allocation'
    ];

    public function step(): BelongsTo
    {
        return $this->belongsTo(PlanStep::class, 'plan_step_id');
    }

    public function formulaIngredient(): BelongsTo
    {
        return $this->belongsTo(FormulaIngredient::class, 'formula_ingredient_id');
    }
}
