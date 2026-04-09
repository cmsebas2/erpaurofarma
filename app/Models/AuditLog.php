<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\AuditLogObserver;

#[ObservedBy([AuditLogObserver::class])]
class AuditLog extends Model
{
    protected $guarded = [];

    // Audit logs only have created_at, they are never updated.
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
