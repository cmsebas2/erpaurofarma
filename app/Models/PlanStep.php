<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanStep extends Model
{
    protected $fillable = [
        'plan_id',
        'step_number',
        'type',
        'description',
        'theoretical_time_minutes',
        'target_rpm',
        'mesh_size',
        'ipc_test_type',
        'ipc_specification'
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(ProductManufacturingPlan::class, 'plan_id');
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(PlanStepIngredient::class, 'plan_step_id');
    }
}
