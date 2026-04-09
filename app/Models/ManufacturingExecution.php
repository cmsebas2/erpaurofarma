<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'plan_step_id',
        'plan_step_ingredient_id',
        'step_type',
        'start_time',
        'end_time',
        'rpm',
        'elapsed_minutes',
        'yield_kg',
        'ipc_result',
        'observations',
        'user_id',
        'signed_at',
        'qa_user_id',
        'qa_verified_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'qa_verified_at' => 'datetime',
        'yield_kg' => 'decimal:2',
        'elapsed_minutes' => 'decimal:2',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function planStep()
    {
        return $this->belongsTo(PlanStep::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qaUser()
    {
        return $this->belongsTo(User::class, 'qa_user_id');
    }
}
