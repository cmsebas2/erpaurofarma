<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineClearance extends Model
{
    protected $fillable = [
        'production_order_id',
        'area',
        'fecha_inicio',
        'hora_inicio',
        'producto_anterior',
        'lote_anterior',
        'respuestas_checklist',
        'diferencial_presion',
        'fecha_fin',
        'hora_fin',
        'realizado_por',
        'verificado_por',
        'qa_presion_diferencial_conforme',
    ];

    protected $casts = [
        'respuestas_checklist' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function realizadoPor()
    {
        return $this->belongsTo(User::class, 'realizado_por');
    }

    public function verificadoPor()
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }
}
