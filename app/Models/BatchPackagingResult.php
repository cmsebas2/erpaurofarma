<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchPackagingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'color_conforme',
        'odor_conforme',
        'texture_conforme',
        'particles_free',
        'weight_1', 'weight_2', 'weight_3', 'weight_4', 'weight_5',
        'weight_6', 'weight_7', 'weight_8', 'weight_9', 'weight_10',
        'average_weight',
        'start_time',
        'end_time',
        'status',
        'user_id',
        'signed_at',
        'qa_user_id',
        'qa_verified_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'qa_verified_at' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'color_conforme' => 'boolean',
        'odor_conforme' => 'boolean',
        'texture_conforme' => 'boolean',
        'particles_free' => 'boolean',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function qaUser()
    {
        return $this->belongsTo(User::class, 'qa_user_id');
    }
}
