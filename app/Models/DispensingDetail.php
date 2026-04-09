<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispensingDetail extends Model
{
    protected $fillable = [
        'dispensing_id',
        'formula_ingredient_id',
        'lote_mp',
        'fecha',
        'cantidad_teorica',
        'cantidad_real',
        'hora_inicio',
        'hora_final',
        'realizado_por',
        'verificado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function dispensing()
    {
        return $this->belongsTo(Dispensing::class);
    }

    public function formulaIngredient()
    {
        return $this->belongsTo(FormulaIngredient::class);
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
