<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableTrait;

class Item extends Model
{
    use AuditableTrait;

    protected $fillable = [
        'item_code',
        'reference',
        'description',
        'ext_1_detail',
        'ext_2_detail',
        'inventory_type',
        'item_type',
        'tax_group',
        'discount_group',
        'inventory_uom',
        'order_uom',
        'packaging_uom',
        'is_purchased',
        'is_sold',
        'is_manufactured',
        'has_extension',
        'manages_batches',
        'batch_assignment',
        'manages_serial',
    ];
    
    protected $casts = [
        'is_purchased' => 'boolean',
        'is_sold' => 'boolean',
        'is_manufactured' => 'boolean',
        'has_extension' => 'boolean',
        'manages_batches' => 'boolean',
        'batch_assignment' => 'boolean',
        'manages_serial' => 'boolean',
    ];

    public function getCodigoAttribute()
    {
        return $this->item_code;
    }

    public function getNombreAttribute()
    {
        return $this->description;
    }

    public function getTipoMaterialAttribute()
    {
        return $this->inventory_type;
    }

    public function getUnidadMedidaAttribute()
    {
        return $this->inventory_uom ?? 'UND';
    }
}
