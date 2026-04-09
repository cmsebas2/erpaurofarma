<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableTrait;
use App\Traits\SignatureTrait;

class ProductionOrder extends Model
{
    use AuditableTrait, SignatureTrait;

    protected $fillable = [
        'op_number',
        'product_id',
        'lote',
        'bulk_size_kg',
        'unit',
        'manufacturing_date',
        'expiration_date',
        'destruction_date',
        'maquilador',
        'status',
    ];

    public function getRouteKeyName()
    {
        return 'lote';
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['COMPLETADO', 'ANULADO']);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function opPresentations()
    {
        return $this->hasMany(OpPresentation::class);
    }

    public function lineClearances()
    {
        return $this->hasMany(LineClearance::class, 'production_order_id');
    }

    public function materialReconciliations()
    {
        return $this->hasMany(OpMaterialReconciliation::class, 'production_order_id');
    }

    public function dispensing()
    {
        return $this->hasOne(Dispensing::class);
    }

    public function manufacturingExecutions()
    {
        return $this->hasMany(ManufacturingExecution::class, 'production_order_id');
    }

    /**
     * SEGURIDAD BPM: Verifica si un paso o ingrediente ya está firmado.
     */
    public function pasoEstaFirmado($stepId, $ingredientId = null)
    {
        return $this->manufacturingExecutions()
            ->where('plan_step_id', $stepId)
            ->where('plan_step_ingredient_id', $ingredientId)
            ->whereNotNull('signed_at')
            ->exists();
    }

    public function packagingResult()
    {
        return $this->hasOne(BatchPackagingResult::class, 'production_order_id');
    }

    public function packagingWeightControls()
    {
        return $this->hasMany(BatchPackagingWeightControl::class, 'production_order_id');
    }
}

