<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductManufacturingPlan extends Model
{
    protected $fillable = [
        'product_id',
        'master_code', // Still keeping this for backward compatibility if needed, but UI will use master_code_header
        'master_code_header',
        'internal_code',
        'version',
        'issue_date',
        'ica_registry',
        'objective',
        'requirements',
        'equipment',
        'potency_adjustment_logic',
        'observations',
        'sterilization_method',
        'master_batch_size',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'issue_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(PlanStep::class, 'plan_id')->orderBy('step_number');
    }
}
