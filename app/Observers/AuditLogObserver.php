<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogObserver
{
    /**
     * Handle the AuditLog "created" event.
     */
    public function created(Model $model): void
    {
        // Si queremos auto-loguear en el futuro lo hacemos aquí
    }

    /**
     * Handle the AuditLog "updating" event.
     */
    public function updating(Model $model): void
    {
        if ($model instanceof \App\Models\AuditLog) {
            throw new \Exception('Los registros de Audit Log son inalterables (CFR 21 Parte 11)');
        }
    }

    /**
     * Handle the AuditLog "deleting" event.
     */
    public function deleting(Model $model): void
    {
        if ($model instanceof \App\Models\AuditLog) {
            throw new \Exception('Los registros de Audit Log no pueden ser eliminados (CFR 21 Parte 11)');
        }
    }
}
