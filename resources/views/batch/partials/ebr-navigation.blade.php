@php
    $status = isset($op) ? $op->status : 'PLANEADO';
    $lote = isset($op) ? $op->lote : null;
    
    // Logic for accessibility
    $canAccessConciliacion = isset($op); 
    $canAccessDespeje = isset($op); 
    $canAccessDispensacion = isset($op);
    $canAccessFabricacion = isset($op);
    $canAccessEnvase = isset($op) && ($status === 'ACONDICIONAMIENTO' || $status === 'COMPLETADO');
    
    // Check if current page is one of the steps to highlight
    $currentRoute = Route::currentRouteName();
    
    $steps = [
        ['id' => 1, 'label' => 'Apertura OP', 'route' => 'batch.iniciar', 'active' => ($currentRoute == 'batch.iniciar')],
        ['id' => 2, 'label' => 'Conciliación', 'route' => 'batch.conciliacion', 'active' => ($currentRoute == 'batch.conciliacion')],
        ['id' => 3, 'label' => 'Despeje', 'route' => 'batch.despeje', 'active' => ($currentRoute == 'batch.despeje')],
        ['id' => 4, 'label' => 'Dispensación', 'route' => 'batch.dispensacion', 'active' => ($currentRoute == 'batch.dispensacion')],
        ['id' => 5, 'label' => 'Fabricación', 'route' => 'batch.fabricacion', 'active' => ($currentRoute == 'batch.fabricacion')],
        ['id' => 6, 'label' => 'Envase', 'route' => 'batch.despeje', 'active' => ($currentRoute == 'batch.envase' || $currentRoute == 'batch.despeje')],
    ];

    $canAccess = [
        1 => true,
        2 => $canAccessConciliacion,
        3 => $canAccessDespeje,
        4 => $canAccessDispensacion, 
        5 => $canAccessFabricacion,
        6 => $canAccessEnvase
    ];
@endphp

<div class="mb-8">
    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-6">Secuencia EBR - Flujo de Proceso</h2>
    <div class="flex items-center justify-between relative px-4">
        <!-- Connecting Line -->
        <div class="absolute left-0 top-5 transform -translate-y-1/2 w-full h-1 bg-gray-200 z-0"></div>
        
        @foreach($steps as $step)
            @php
                $isAccessible = $canAccess[$step['id']];
                $active = $step['active'];
                $completed = ($step['id'] < 5) || ($step['id'] == 5 && $canAccessEnvase); // Approximation
                
                $circleClass = $active ? 'bg-aurofarma-blue text-white ring-4 ring-blue-100' : 
                              ($isAccessible ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed');
                
                $textClass = $active ? 'text-aurofarma-blue font-black' : 
                            ($isAccessible ? 'text-green-600 font-bold' : 'text-gray-400 font-medium');
            @endphp
            
            <div class="relative z-10 flex flex-col items-center">
                @if($isAccessible)
                    @php
                        $routeParams = ($step['route'] === 'batch.iniciar') ? [] : [$lote];
                    @endphp
                    <a href="{{ route($step['route'], $routeParams) }}" class="flex flex-col items-center group">
                @else
                    <div class="flex flex-col items-center">
                @endif

                <div class="w-10 h-10 rounded-full {{ $circleClass }} flex items-center justify-center font-bold shadow-md transition-all duration-300">
                    @if($step['id'] == 6)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    @elseif($step['id'] < 6 && $completed && !$active)
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        {{ $step['id'] }}
                    @endif
                </div>
                <span class="text-[10px] mt-2 uppercase tracking-tighter {{ $textClass }}">{{ $step['label'] }}</span>

                @if($isAccessible)
                    </a>
                @else
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
