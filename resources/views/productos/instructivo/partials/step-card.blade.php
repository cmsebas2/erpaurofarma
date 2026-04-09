@php
    $stepType = $step ? $step->type : 'TEXTO';
    $description = $step ? $step->description : '';
    $stepIndex = is_numeric($index) ? $index + 1 : 'STEP_NUM_HOLDER';
    
    // Detectar si tiene parámetros guardados para mostrarlos por defecto
    $hasTime = $step && !empty($step->theoretical_time_minutes);
    $hasSpeed = $step && !empty($step->target_rpm);
@endphp

{{-- FILA 1: INSTRUCCIÓN / ACTIVIDAD PRINCIPAL --}}
<tr class="step-card-wrapper border-b-2 border-black group relative" data-step-index="{{ $index }}">
    <!-- ACTIVIDAD (35%) -->
    <td class="border-2 border-black p-4 align-top text-start" style="width: 35%;">
        <div class="flex flex-col gap-2 overflow-hidden">
            <div class="flex justify-between items-center bg-gray-50 p-1 border border-gray-200">
                <select name="steps[{{ $index }}][type]" onchange="toggleStepType(this, {{ $index }})" 
                        class="bg-transparent border-none p-0 text-[10px] font-black uppercase focus:ring-0">
                    <option value="TEXTO" {{ $stepType == 'TEXTO' ? 'selected' : '' }}>TEXTO / INSTRUCCIÓN</option>
                    <option value="CARGA" {{ $stepType == 'CARGA' ? 'selected' : '' }}>ADICIÓN (CARGA)</option>
                    <option value="MEZCLA" {{ $stepType == 'MEZCLA' ? 'selected' : '' }}>MEZCLADO (RPM/MIN)</option>
                    <option value="TAMIZADO" {{ $stepType == 'TAMIZADO' ? 'selected' : '' }}>TAMIZADO (MALLA)</option>
                    <option value="CONTROL_PROCESO" {{ $stepType == 'CONTROL_PROCESO' ? 'selected' : '' }}>CONTROL EN PROCESO</option>
                </select>
                <div class="text-[8px] text-gray-400 uppercase">PASO #<span class="step-number">{{ $stepIndex }}</span></div>
            </div>

            <div class="flex flex-col">
                <textarea name="steps[{{ $index }}][description]" rows="2" required 
                          class="w-full border-none p-1 text-[12px] font-normal focus:ring-0 leading-tight uppercase" 
                          placeholder="Describa la actividad detalladamente...">{{ $description }}</textarea>
                
                {{-- BOTONES DE CONTROL DE PARÁMETROS (Sólo para MEZCLA) --}}
                <div id="step-{{ $index }}-params-actions" class="flex gap-2 mt-1 hidden-print {{ $stepType == 'MEZCLA' ? '' : 'hidden' }}">
                    <button type="button" onclick="toggleParameter({{ $index }}, 'time')" 
                            class="text-[9px] px-2 py-0.5 bg-gray-100 border border-gray-300 rounded hover:bg-blue-50 font-bold uppercase transition-colors">
                        {{ $hasTime ? '- Quitar Tiempo' : '+ Agregar Tiempo' }}
                    </button>
                    <button type="button" onclick="toggleParameter({{ $index }}, 'speed')" 
                            class="text-[9px] px-2 py-0.5 bg-gray-100 border border-gray-300 rounded hover:bg-blue-50 font-bold uppercase transition-colors">
                        {{ $hasSpeed ? '- Quitar Velocidad' : '+ Agregar Velocidad' }}
                    </button>
                </div>

                {{-- CONTENEDOR DE PARÁMETROS DINÁMICOS (Mezclado) --}}
                <div id="step-{{ $index }}-params" class="mt-2 border-t border-gray-200 pt-2 p-1 {{ $stepType == 'MEZCLA' ? '' : 'hidden' }}">
                    <div class="flex flex-col gap-3">
                        {{-- TIEMPO --}}
                        <div id="step-{{ $index }}-param-time" class="flex flex-col gap-1 {{ $hasTime ? '' : 'hidden' }}">
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] font-black uppercase text-blue-800">Tiempo Teórico:</span>
                                <input type="number" step="0.01" name="steps[{{ $index }}][mixing_time]" 
                                       id="input-time-{{ $index }}"
                                       value="{{ $step->theoretical_time_minutes ?? '' }}"
                                       class="w-16 bg-transparent border-b border-gray-300 p-0 text-[13px] font-black text-center focus:ring-0" 
                                       placeholder="0.00">
                                <span class="text-[10px] font-bold">min</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold uppercase text-gray-500">Tiempo Real:</span>
                                <span class="text-[15px] border-b border-black w-24 text-center">_______ min</span>
                            </div>
                        </div>

                        {{-- VELOCIDAD --}}
                        <div id="step-{{ $index }}-param-speed" class="flex flex-col gap-1 {{ $hasSpeed ? '' : 'hidden' }}">
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] font-black uppercase text-blue-800">Velocidad Teórica:</span>
                                <input type="number" step="0.01" name="steps[{{ $index }}][agitation_speed]" 
                                       id="input-speed-{{ $index }}"
                                       value="{{ $step->target_rpm ?? '' }}"
                                       class="w-20 bg-transparent border-b border-gray-300 p-0 text-[13px] font-black text-center focus:ring-0" 
                                       placeholder="0.00">
                                <span class="text-[10px] font-bold">rpm</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold uppercase text-gray-500">Velocidad Real:</span>
                                <span class="text-[15px] border-b border-black w-24 text-center">_______ rpm</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONTENEDOR CONTROL EN PROCESO (IPC) --}}
                <div id="step-{{ $index }}-ipc" class="mt-2 border-t border-gray-200 pt-2 p-1 {{ $stepType == 'CONTROL_PROCESO' ? '' : 'hidden' }}">
                    <div class="bg-blue-50/30 p-2 border border-blue-100 rounded space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase text-blue-900">PRUEBA:</span>
                            <select name="steps[{{ $index }}][ipc_test_type]" class="flex-1 bg-white border border-gray-300 text-[12px] font-medium p-1 focus:ring-0">
                                <option value="Aspecto / Características organolépticas" {{ ($step->ipc_test_type ?? '') == 'Aspecto / Características organolépticas' ? 'selected' : '' }}>Aspecto / Características organolépticas</option>
                                <option value="Peso" {{ ($step->ipc_test_type ?? '') == 'Peso' ? 'selected' : '' }}>Peso</option>
                                <option value="pH" {{ ($step->ipc_test_type ?? '') == 'pH' ? 'selected' : '' }}>pH</option>
                                <option value="Densidad" {{ ($step->ipc_test_type ?? '') == 'Densidad' ? 'selected' : '' }}>Densidad</option>
                                <option value="Viscosidad" {{ ($step->ipc_test_type ?? '') == 'Viscosidad' ? 'selected' : '' }}>Viscosidad</option>
                                <option value="Humedad (LOD - Pérdida por secado)" {{ ($step->ipc_test_type ?? '') == 'Humedad (LOD - Pérdida por secado)' ? 'selected' : '' }}>Humedad (LOD - Pérdida por secado)</option>
                                <option value="Variación de peso / Peso promedio" {{ ($step->ipc_test_type ?? '') == 'Variación de peso / Peso promedio' ? 'selected' : '' }}>Variación de peso / Peso promedio</option>
                                <option value="Volumen de llenado" {{ ($step->ipc_test_type ?? '') == 'Volumen de llenado' ? 'selected' : '' }}>Volumen de llenado</option>
                                <option value="Dureza" {{ ($step->ipc_test_type ?? '') == 'Dureza' ? 'selected' : '' }}>Dureza</option>
                                <option value="Friabilidad" {{ ($step->ipc_test_type ?? '') == 'Friabilidad' ? 'selected' : '' }}>Friabilidad</option>
                                <option value="Desintegración" {{ ($step->ipc_test_type ?? '') == 'Desintegración' ? 'selected' : '' }}>Desintegración</option>
                                <option value="Hermeticidad / Prueba de fugas" {{ ($step->ipc_test_type ?? '') == 'Hermeticidad / Prueba de fugas' ? 'selected' : '' }}>Hermeticidad / Prueba de fugas</option>
                                <option value="Otro" {{ ($step->ipc_test_type ?? '') == 'Otro' ? 'selected' : '' }}>Otro (Campo de texto abierto)</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase text-gray-500">ESPECIFICACIÓN:</span>
                            <input type="text" name="steps[{{ $index }}][ipc_specification]" value="{{ $step->ipc_specification ?? '' }}" 
                                   class="flex-1 bg-white border border-gray-300 text-[12px] p-1 focus:ring-0" placeholder="Ej: 4.5 - 5.5">
                        </div>
                        <div class="text-[12px] font-medium flex items-center gap-2">
                            <span class="uppercase">REAL:</span>
                            <span class="flex-1 border-b border-black">___________________</span>
                        </div>
                    </div>
                </div>

                {{-- El selector se movió a su propia fila <tr> para mantener la estructura de la tabla --}}
                <div id="step-{{ $index }}-builder" class="mt-2 border-t border-dashed border-gray-300 pt-2 p-1 hidden-print overflow-hidden {{ $stepType == 'CARGA' ? '' : 'hidden' }}">
                     {{-- Contenido de apoyo o placeholder si se requiere --}}
                </div>
            </div>
        </div>
        
        {{-- BOTÓN ELIMINAR PASO (FLOTANTE FUERA DE LA TABLA) --}}
        <button type="button" onclick="removeStep(this)" 
                class="absolute -right-10 top-1/2 -translate-y-1/2 text-red-400 hover:text-red-600 p-2 hidden-print opacity-0 group-hover:opacity-100 transition-opacity"
                title="Eliminar Paso">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
    </td>

    <!-- COLUMNAS DE REGISTRO (15px Normal, Padding p-4) -->
    <td class="border-2 border-black p-4 text-center align-middle font-normal" style="font-size: 15px; width: 5%;">---</td> {{-- Unidad --}}
    <td class="border-2 border-black p-4 text-center align-middle font-normal" style="font-size: 15px; width: 10%;">---</td> {{-- Teórica --}}
    <td class="border-2 border-black p-4 text-center align-middle font-normal" style="font-size: 15px; width: 10%;">---</td> {{-- Utilizada --}}
    <td class="border-2 border-black p-4 text-center align-middle bg-gray-50 font-normal" style="font-size: 15px; width: 10%;">
        {{-- Espacio limpio para Fecha --}}
    </td>
    <td class="border-2 border-black p-4 text-center align-middle font-normal" style="font-size: 15px; width: 5%;">
        {{-- Espacio limpio Inicial --}}
    </td>
    <td class="border-2 border-black p-4 text-center align-middle font-normal" style="font-size: 15px; width: 5%;">
        {{-- Espacio limpio Final --}}
    </td>
    <td class="border-2 border-black p-4 text-center align-middle font-normal" style="font-size: 15px; width: 10%;">
        {{-- Espacio limpio Realizó --}}
    </td>
    <td class="border-2 border-black p-4 text-center align-middle font-normal" style="font-size: 15px; width: 10%;">
        {{-- Espacio limpio Verificó --}}
    </td>
</tr>

@if($stepType == 'CARGA')
    {{-- FILA DE ACCIÓN: AGREGAR MATERIAL (Ahora al inicio del bloque de carga) --}}
    <tr class="step-action-row bg-slate-50/50 hidden-print {{ $stepType == 'CARGA' ? '' : 'hidden' }}" 
        id="step-{{ $index }}-action-row" 
        data-step-parent="{{ $index }}">
        <td colspan="9" class="p-3 border-2 border-black border-t-dashed">
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black text-blue-900 uppercase whitespace-nowrap">
                    <svg class="w-4 h-4 inline me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Añadir materia prima:
                </span>
                <select class="form-select text-[11px] font-bold border-gray-300 rounded-md shadow-sm focus:ring-blue-500 py-1" 
                        style="max-width: 400px;"
                        onchange="if(this.value) { addIngredientRow({{ $index }}, this); this.value=''; }">
                    <option value="">Seleccione para agregar a la lista...</option>
                    @foreach($producto->ingredients as $pIng)
                        <option value="{{ $pIng->id }}" 
                                data-name="{{ $pIng->material_name }}" 
                                data-unit="{{ $pIng->unit ?? 'KG' }}"
                                data-percentage="{{ $pIng->percentage }}">
                            {{ $pIng->material_name }} ({{ $pIng->material_code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </td>
    </tr>

    @php
        $ingredients = ($step && count($step->ingredients) > 0) ? $step->ingredients : [];
    @endphp
    @foreach($ingredients as $ingIdx => $ing)
        @php
            $pIng = $producto->ingredients->firstWhere('id', $ing->formula_ingredient_id);
            $materialName = $pIng ? $pIng->material_name : 'Material Desconocido';
        @endphp
        <tr class="step-material-row border-b-2 border-black group/mat" data-step-parent="{{ $index }}" data-material-index="{{ $ingIdx }}">
            <!-- ACTIVIDAD: Nombre + Delete -->
            <td class="border-2 border-black p-4 pl-10 text-start align-middle relative" style="width: 35%;">
                <input type="hidden" class="ing-id" value="{{ $ing->formula_ingredient_id }}">
                <span class="font-bold text-gray-800 uppercase block truncate" style="font-size: 12px;" title="{{ $materialName }}">
                    • {{ $materialName }}
                </span>
                <button type="button" onclick="this.closest('tr').remove()" 
                        class="absolute left-2 top-1/2 -translate-y-1/2 text-red-400 hover:text-red-600 font-bold text-[18px] opacity-0 group-hover/mat:opacity-100 transition-opacity">
                    ×
                </button>
            </td>

            <!-- UNIDAD (5%) -->
            <td class="border-2 border-black p-2 text-center align-middle" style="width: 5%;">
                <select class="ing-unit w-full bg-transparent border-none p-0 text-[13px] font-black text-center focus:ring-0">
                    <option value="KG" {{ ($ing->unit ?? 'KG') == 'KG' ? 'selected' : '' }}>KG</option>
                    <option value="L" {{ ($ing->unit ?? 'KG') == 'L' ? 'selected' : '' }}>L</option>
                </select>
            </td>

            <!-- TEÓRICA (10%) -->
            <td class="border-2 border-black p-2 text-center align-middle" style="width: 10%;">
                <input type="number" step="0.01" class="ing-quantity w-full bg-transparent border-none p-0 text-[13px] font-black text-center focus:ring-0" 
                       value="{{ number_format($ing->theoretical_quantity ?? $ing->percentage_allocation, 2, '.', '') }}"
                       data-percentage="{{ $pIng->percentage ?? 0 }}"
                       placeholder="0.00">
            </td>

            <!-- UTILIZADA (10%) -->
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;">---</td>

            <!-- ESPACIOS DE REGISTRO (Vacíos para el maestro) -->
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 5%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 5%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;"></td>
        </tr>
    @endforeach
@endif
