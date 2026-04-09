@extends('layouts.app')

@section('header_title', 'Inicio (Dashboard)')

@section('content')
<div class="space-y-6">
    <!-- KPIs Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tarjeta 1: OPs Activas -->
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-aurofarma-blue p-6 flex items-center">
            <div class="p-4 rounded-full bg-aurofarma-blue/10 text-aurofarma-blue mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">OPs Activas</p>
                <div class="text-3xl font-bold text-gray-800">{{ $activeOrdersCount }}</div>
            </div>
        </div>

        <!-- Tarjeta 2: Alertas Calibración -->
        <div class="bg-white rounded-xl shadow-sm border-l-4 {{ $calibrationAlertsCount > 0 ? 'border-aurofarma-orange' : 'border-aurofarma-teal' }} p-6 flex items-center">
            <div class="p-4 rounded-full {{ $calibrationAlertsCount > 0 ? 'bg-aurofarma-orange/10 text-aurofarma-orange' : 'bg-aurofarma-teal/10 text-aurofarma-teal' }} mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Calibración Próxima (30D)</p>
                <div class="text-3xl font-bold text-gray-800">{{ $calibrationAlertsCount }}</div>
            </div>
        </div>

        <!-- Tarjeta 3: Lotes Liberados -->
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-aurofarma-teal p-6 flex items-center">
            <div class="p-4 rounded-full bg-aurofarma-teal/10 text-aurofarma-teal mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Liberados (Mes)</p>
                <div class="text-3xl font-bold text-gray-800">{{ $releasedLotsCount }}</div>
            </div>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900 border-l-4 border-aurofarma-blue pl-3">
                Registro de Auditoría (Audit Trail) - CFR 21 P11
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha / Hora</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuario</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acción</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Módulo (Modelo)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cambio (Antes -> Después)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($auditLogs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $log->user ? $log->user->name : 'Sistema / Seeder' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->ip_address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full uppercase tracking-wider
                                    @if($log->action == 'created') bg-aurofarma-teal/10 text-aurofarma-teal
                                    @elseif($log->action == 'updated') bg-aurofarma-orange/10 text-aurofarma-orange
                                    @else bg-aurofarma-red/10 text-aurofarma-red @endif
                                ">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $log->new_values }}">
                                <span class="text-aurofarma-red line-through mr-1 font-medium">{{ $log->old_values ?: 'N/A' }}</span>
                                <span class="text-aurofarma-teal font-bold">➔ {{ Str::limit($log->new_values, 60) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium whitespace-nowrap">
                                No hay registros de auditoría disponibles.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($auditLogs, 'links'))
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
