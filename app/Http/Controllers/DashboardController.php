<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\Equipment;
use App\Models\AuditLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Tarjeta 1: OPs Activas (Not completed/released)
        $activeOrdersCount = ProductionOrder::whereNotIn('status', ['Terminada', 'Liberada', 'Cancelada'])->count();

        // Tarjeta 2: Alertas de Calibración
        $thirtyDaysFromNow = Carbon::now()->addDays(30);
        $calibrationAlertsCount = Equipment::where('is_active', true)
            ->whereDate('next_calibration_date', '<=', $thirtyDaysFromNow)
            ->count();

        // Tarjeta 3: Lotes Liberados este mes
        $releasedLotsCount = ProductionOrder::where('status', 'Liberada')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();

        // Tabla: Últimas acciones de Auditoría (Audit Log)
        $auditLogs = AuditLog::with('user')->latest()->take(50)->get();

        return view('dashboard', compact('activeOrdersCount', 'calibrationAlertsCount', 'releasedLotsCount', 'auditLogs'));
    }
}
