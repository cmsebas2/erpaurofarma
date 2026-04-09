<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispensing extends Model
{
    protected $fillable = [
        'production_order_id',
        'observaciones',
        'fecha_inicio',
        'fecha_fin',
        'realizado_por',
        'fecha_realizado',
        'verificado_por',
        'fecha_verificado',
        'status',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_realizado' => 'datetime',
        'fecha_verificado' => 'datetime',
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

    public function dispensingDetails()
    {
        return $this->hasMany(DispensingDetail::class);
    }
}
