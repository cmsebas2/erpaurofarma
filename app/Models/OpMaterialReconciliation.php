<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableTrait;

class OpMaterialReconciliation extends Model
{
    use AuditableTrait;

    protected $fillable = [
        'production_order_id',
        'type',
        'description',
        'unit',
        'lote',
        'received_qty',
        'used_qty',
        'returned_qty',
        'date',
        'signed_by',
        'signed_at',
        'qa_user_id',
        'qa_verified_at',
        'observations',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function signedByUser()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function qaUser()
    {
        return $this->belongsTo(User::class, 'qa_user_id');
    }
}
