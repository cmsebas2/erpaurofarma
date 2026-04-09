@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <form action="{{ route('productos.instructivo.update', $producto->id) }}" method="POST" id="ebr-builder-form">
        @csrf
        
        <!-- EXCEL STYLE DOCUMENT WRAPPER -->
        <div class="bg-white p-8 shadow-2xl border border-gray-300 min-h-screen font-sans text-gray-900">
            
            <!-- 1. ENCABEZADO NORMATIVO (REDISEÑO DE ALTA PRECISIÓN IF-304-1) -->
            <style>
                /* Forzamos el grosor de línea en toda la estructura del encabezado */
                .tabla-auro {
                    width: 100%;
                    border-collapse: collapse;
                    border: 2px solid black;
                    margin-bottom: 0;
                }
                .tabla-auro td, .tabla-auro th {
                    border: 2px solid black;
                    color: black;
                }
                /* Quitamos el borde de la tabla anidada para no duplicar líneas */
                .tabla-interna td {
                    border: none;
                }
                .fw-bold { font-weight: 700 !important; }
                .fw-normal { font-weight: 400 !important; }
            </style>

            <table class="tabla-auro align-middle">
                <tbody>
                    <tr>
                    <td class="text-start p-1 uppercase" style="width: 25%; font-size: 15px;">
                        <span class="fw-bold">CÓDIGO:</span> <span class="fw-normal">A1PPR0010</span>
                    </td>
                    <td rowspan="4" class="text-center fw-bold uppercase tracking-tight bg-gray-50" style="width: 50%; font-size: 18px;">
                        INSTRUCTIVO DE FABRICACIÓN, ENVASE Y EMPAQUE
                    </td>
                    <td rowspan="4" class="text-center p-2" style="width: 25%;">
                        <img src="{{ asset('img/logo.png') }}" alt="AUROFARMA" style="max-height: 60px;" class="mx-auto grayscale opacity-80 mix-blend-multiply">
                    </td>
                </tr>
                <tr>
                    <td class="text-start p-1 uppercase" style="font-size: 15px;">
                        <span class="fw-bold">VERSIÓN:</span> <span class="fw-normal">03</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-start p-1" style="font-size: 15px;">
                        <span class="fw-bold">Fecha de emisión:</span> 
                        <span class="fw-normal">{{ \Carbon\Carbon::parse($plan->issue_date ?? now())->format('Y-m-d') }}</span>
                        <input type="hidden" name="issue_date" value="{{ \Carbon\Carbon::parse($plan->issue_date ?? now())->format('Y-m-d') }}">
                    </td>
                </tr>
                <tr>
                    <td class="text-start p-1 text-center uppercase" style="font-size: 15px;">
                        <span class="fw-bold">Página</span> <span class="fw-normal">1 de 1</span>
                    </td>
                </tr>

                <tr>
                    <td class="p-0 align-middle" style="width: 22%;">
                        <table class="tabla-interna" style="width: 100%; border-collapse: collapse; margin: 0;">
                            <tr>
                                <td class="p-2 text-start" style="border-bottom: 2px solid black;">
                                    <span class="fw-bold" style="font-size: 15px;">Registro ICA:</span> 
                                    <span class="fw-normal ms-1" style="font-size: 15px;">{{ $producto->ica_license ?? 'NO REGISTRA' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 text-start">
                                    <span class="fw-bold" style="font-size: 15px;">Fórmula maestra:</span> 
                                    <span class="fw-normal ms-1" style="font-size: 15px;">{{ $producto->formula_maestra ?? 'SIN ASIGNAR' }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td class="text-center align-middle p-2" style="width: 58%;">
                        <div class="fw-bold text-gray-600" style="font-size: 12px; text-transform: uppercase;">NOMBRE DE PRODUCTO:</div>
                        <div class="fw-bold text-blue-900 text-uppercase mt-1" style="font-size: 20px; line-height: 1;">
                            {{ $producto->name ?? 'Q-MUTIN' }}
                        </div>
                        
                        <div class="d-flex justify-content-center align-items-center mt-2">
                            <span class="fw-bold me-2 uppercase text-gray-600" style="font-size: 15px;">TAMAÑO DE LOTE:</span>
                            <input type="number" step="0.01" name="master_batch_size" value="{{ old('master_batch_size', number_format($plan->master_batch_size, 2, '.', '')) }}" 
                                   id="master_batch_size_input"
                                   oninput="updateTheoreticalQuantities()"
                                   class="form-control form-control-sm text-center fw-normal border-gray-300" 
                                   style="width: 110px; font-size: 15px;" 
                                   placeholder="Ej: 1000.00">
                            <span class="fw-bold ms-2" style="font-size: 15px;">KG</span>
                        </div>
                    </td>

                    <td class="text-center align-middle p-2" style="width: 20%;">
                        <div class="fw-bold text-gray-600" style="font-size: 15px;">CÓDIGO:</div>
                        <input type="text" name="master_code_header" value="{{ old('master_code_header', $plan->master_code_header) }}" 
                               class="form-control form-control-sm border-0 bg-transparent text-center fw-normal text-blue-800 p-0" 
                               style="font-size: 15px; box-shadow: none;" placeholder="Ej: INS-PROD-001">
                    </td>
                </tr>
            </tbody>
        </table>

            <!-- 2. CUERPO DEL DOCUMENTO -->
            <div class="mt-6 space-y-4">
                
                <!-- 1. OBJETIVO -->
                <div class="border-2 border-black">
                    <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">1. OBJETIVO</div>
                    <textarea name="objective" rows="2" class="w-full border-none p-3 focus:ring-0 content-normal" style="font-size: 15px;" placeholder="Redacte el objetivo del instructivo...">{{ old('objective', $plan->objective) }}</textarea>
                </div>

                <!-- 2. REQUERIMIENTOS PREVIOS -->
                <div class="border-2 border-black">
                    <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">2. REQUERIMIENTOS PREVIOS</div>
                    <textarea name="requirements" rows="6" class="w-full border-none p-3 focus:ring-0 leading-relaxed content-normal" 
                              style="font-size: 15px;"
                              placeholder="2.1 El operario debe...&#10;2.2 Verificación de limpieza...&#10;2.3 Despeje de línea...&#10;2.4 Verificación de equipos...">{{ old('requirements', $plan->requirements) ?: "2.1 El personal asignado debe contar con los elementos de protección personal (EPP) completos y realizar el lavado de manos según protocolo.\n2.2 Verificar que el área y los equipos se encuentren debidamente limpios y con el rótulo de limpieza vigente.\n2.3 Realizar el despeje de línea y verificar que no existan materiales de lotes anteriores.\n2.4 Verificar que las balanzas y equipos de medición se encuentren nivelados y calibrados." }}</textarea>
                </div>

                <!-- 3. EQUIPOS UTILIZADOS -->
                <div class="border-2 border-black">
                    <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">3. EQUIPOS UTILIZADOS</div>
                    <textarea name="equipment" rows="2" class="w-full border-none p-3 focus:ring-0 content-normal" 
                              style="font-size: 15px;"
                              placeholder="Mencione tanques, mezcladores, tamices, balanzas...">{{ old('equipment', $plan->equipment) }}</textarea>
                </div>

                <!-- 4. FABRICACIÓN -->
                <div class="border-2 border-black overflow-hidden">
                    <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">4. FABRICACIÓN</div>
                    
                    <div class="p-4 bg-white">
                        {{-- Bloque de Instrucciones Generales --}}
                        <div class="mb-4">
                            <label class="fw-bold block mb-1" style="font-size: 15px;">Instrucciones de Fabricación:</label>
                            <textarea name="manufacturing_instructions" rows="3" 
                                class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                style="font-size: 15px;"
                                placeholder="Seguir estrictamente los pasos descritos en este instructivo, asegurando la limpieza del área y la verificación de equipos...">{{ old('manufacturing_instructions', $plan->manufacturing_instructions ?? 'Seguir estrictamente los pasos descritos en este instructivo, asegurando la limpieza del área y la verificación de equipos.') }}</textarea>
                        </div>

                        {{-- Tabla de Datos Técnicos Automatizados --}}
                        <table class="tabla-auro" style="width: 100%; border-collapse: collapse; border: 2px solid black;">
                            <tbody>
                                <tr>
                                    <td class="p-2 fw-bold" style="width: 30%; background-color: #f2f2f2; font-size: 15px;">Tamaño de Lote:</td>
                                    <td class="p-2 fw-normal" style="width: 70%; font-size: 15px;">
                                        <span id="fabrication-batch-size-display">{{ number_format($producto->base_batch_size, 2, '.', '') ?? '---' }} KG</span>
                                    </td>
                                </tr>
                                <tr>
                                     <td class="p-2 fw-bold" style="background-color: #f2f2f2; font-size: 15px;">Fecha de fabricación:</td>
                                    <td class="p-2 fw-normal" style="font-size: 15px;">
                                        {{ isset($orden->fecha_fabricacion) ? \Carbon\Carbon::parse($orden->fecha_fabricacion)->format('Y-m-d') : '---' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2 fw-bold" style="background-color: #f2f2f2; font-size: 15px;">Lote:</td>
                                    <td class="p-2 fw-normal" style="font-size: 15px;">
                                        {{ $orden->lote_numero ?? '---' }}
                                    </td>
                                </tr>
                                <tr>
                                     <td class="p-2 fw-bold" style="background-color: #f2f2f2; font-size: 15px;">Fecha de vencimiento:</td>
                                    <td class="p-2 fw-normal" style="font-size: 15px;">
                                        {{ isset($orden->fecha_vencimiento) ? \Carbon\Carbon::parse($orden->fecha_vencimiento)->format('Y-m-d') : '---' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2 fw-bold" style="background-color: #f2f2f2; font-size: 15px;">Laboratorio maquilador:</td>
                                    <td class="p-2 fw-normal" style="font-size: 15px;">
                                        {{ $orden->laboratorio ?? 'AUROFARMA S.A.S.' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. TABLA DINÁMICA DE ACTIVIDADES (EBR BUILDER) -->
            <div class="mt-8 overflow-x-auto">
                <table class="tabla-auro w-full border-collapse text-center" style="border: 2px solid black; font-size: 15px; table-layout: fixed;">
                    <thead class="bg-gray-100 fw-bold" style="border-bottom: 2px solid black; font-size: 12px;">
                        <tr>
                            <th rowspan="2" class="p-2 border-2 border-black" style="width: 35%;">ACTIVIDAD / INSTRUCCIÓN</th>
                            <th colspan="3" class="p-1 border-2 border-black" style="width: 25%;">CANTIDAD</th>
                            <th rowspan="2" class="p-2 border-2 border-black text-center" style="width: 10%;">Fecha</th>
                            <th colspan="2" class="p-1 border-2 border-black" style="width: 10%;">HORA</th>
                            <th colspan="2" class="p-1 border-2 border-black" style="width: 20%;">RESPONSABLE</th>
                        </tr>
                        <tr>
                            <th class="p-1 border-2 border-black" style="width: 5%;">Unidad</th>
                            <th class="p-1 border-2 border-black" style="width: 10%;">Teórica</th>
                            <th class="p-1 border-2 border-black" style="width: 10%;">Utilizada</th>
                            <th class="p-1 border-2 border-black" style="width: 5%;">Inicial</th>
                            <th class="p-1 border-2 border-black" style="width: 5%;">Final</th>
                            <th class="p-1 border-2 border-black" style="width: 10%;">Realizó</th>
                            <th class="p-1 border-2 border-black" style="width: 10%;">Verificó</th>
                        </tr>
                    </thead>
                    <tbody id="steps-container">
                        @php $steps = $plan->steps->count() > 0 ? $plan->steps : [] @endphp
                        
                        @forelse($steps as $index => $step)
                            @include('productos.instructivo.partials.step-card', ['index' => $index, 'step' => $step, 'producto' => $producto])
                        @empty
                            @include('productos.instructivo.partials.step-card', ['index' => 0, 'step' => null, 'producto' => $producto])
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4 flex justify-start">
                    <button type="button" onclick="addStep()" class="bg-black text-white text-[10px] font-black px-4 py-2 hover:bg-gray-800 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        AÑADIR ACTIVIDAD DINÁMICA
                    </button>
                </div>
            </div>

            <!-- 4. PIE DE PÁGINA -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-2 border-black">
                    <div class="bg-black text-white p-1 text-[10px] font-black uppercase pl-2">NOTA: Lógica de Ajuste y Balance</div>
                    <textarea name="potency_adjustment_logic" rows="3" class="w-full border-none p-3 text-xs focus:ring-0 bg-yellow-50/20 content-normal" 
                              placeholder="Ej: Compensar Tiamulina vs Carrier...">{{ old('potency_adjustment_logic', $plan->potency_adjustment_logic) }}</textarea>
                </div>
                <div class="border-2 border-black">
                    <div class="bg-black text-white p-1 text-[10px] font-black uppercase pl-2">OBSERVACIONES:</div>
                    <textarea name="observations" rows="3" class="w-full border-none p-3 text-xs focus:ring-0 content-normal" 
                              placeholder="Indique cualquier observación adicional del proceso maestro...">{{ old('observations', $plan->observations) }}</textarea>
                </div>
                <!-- 5. MÉTODO DE ESTERILIZACIÓN -->
                <div class="border-2 border-black">
                    <div class="bg-black text-white p-1 text-[10px] font-black uppercase pl-2">MÉTODO DE ESTERILIZACIÓN:</div>
                    <textarea name="sterilization_method" rows="3" class="w-full border-none p-3 focus:ring-0 content-normal" 
                              style="font-size: 15px;"
                              placeholder="Describa el método de esterilización utilizado...">{{ old('sterilization_method', $plan->sterilization_method) }}</textarea>
                </div>
            </div>

            <!-- FLOATING ACTION BUTTONS -->
            <div class="mt-12 flex justify-end gap-4 border-t-2 border-gray-100 pt-6 hidden-print">
                <a href="{{ route('productos.edit', $producto->id) }}" class="bg-gray-200 text-gray-800 font-black px-8 py-3 rounded text-sm hover:bg-gray-300 transition-colors">CANCELAR</a>
                
                <button type="button" onclick="window.print()" class="bg-gray-800 text-white font-black px-8 py-3 rounded text-sm hover:bg-gray-700 transition-all uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    IMPRIMIR
                </button>

                <button type="button" onclick="saveInstructivo()" class="bg-slate-900 text-white font-black px-12 py-3 rounded text-sm hover:bg-slate-800 shadow-xl transition-all uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-5 h-5 text-aurofarma-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    GUARDAR INSTRUCTIVO MAESTRO
                </button>
            </div>

        </div>
    </form>
</div>

<!-- Template for JS cloning -->
<template id="step-template">
    @include('productos.instructivo.partials.step-card', ['index' => 'ID_HOLDER', 'step' => null, 'producto' => $producto])
</template>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let stepCount = {{ max(1, $plan->steps->count()) }};

    function saveInstructivo() {
        // Collect Header Data
        const formData = {
            master_code_header: document.querySelector('[name="master_code_header"]').value,
            internal_code: 'IF-304-1', // Fixed for this version
            version: '01', // Standardized
            issue_date: document.querySelector('[name="issue_date"]').value,
            objective: document.querySelector('[name="objective"]').value,
            requirements: document.querySelector('[name="requirements"]').value,
            equipment: document.querySelector('[name="equipment"]').value,
            potency_adjustment_logic: document.querySelector('[name="potency_adjustment_logic"]').value,
            observations: document.querySelector('[name="observations"]').value,
            sterilization_method: document.querySelector('[name="sterilization_method"]').value,
            master_batch_size: document.getElementById('master_batch_size_input').value,
            steps: []
        };

        // Collect Steps Data
        document.querySelectorAll('.step-card-wrapper').forEach((stepRow, index) => {
            const stepId = stepRow.dataset.stepIndex;
            const stepEntry = {
                type: stepRow.querySelector(`[name="steps[${stepId}][type]"]`).value,
                description: stepRow.querySelector(`[name="steps[${stepId}][description]"]`).value,
                theoretical_time_minutes: stepRow.querySelector(`[name="steps[${stepId}][mixing_time]"]`)?.value || null,
                target_rpm: stepRow.querySelector(`[name="steps[${stepId}][agitation_speed]"]`)?.value || null,
                ipc_test_type: stepRow.querySelector(`[name="steps[${stepId}][ipc_test_type]"]`)?.value || null,
                ipc_specification: stepRow.querySelector(`[name="steps[${stepId}][ipc_specification]"]`)?.value || null,
                ingredients: []
            };

            // Collect Ingredients for this step (Individual Table Rows)
            document.querySelectorAll(`tr.step-material-row[data-step-parent="${stepId}"]`).forEach(ingRow => {
                stepEntry.ingredients.push({
                    id: ingRow.querySelector('.ing-id').value,
                    unit: ingRow.querySelector('.ing-unit').value,
                    theoretical_quantity: ingRow.querySelector('.ing-quantity').value
                });
            });

            formData.steps.push(stepEntry);
        });

        // AJAX POST
        Swal.fire({
            title: 'Guardando...',
            text: 'Validando y persistiendo instructivo maestro',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('{{ route('productos.instructivo.update', $producto->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
        });
    }

    function addStep() {
        const container = document.getElementById('steps-container');
        const template = document.getElementById('step-template').innerHTML;
        const newStepStr = template.replace(/ID_HOLDER/g, stepCount)
                                   .replace(/STEP_NUM_HOLDER/g, stepCount + 1);
        
        const tempDiv = document.createElement('tbody');
        tempDiv.innerHTML = newStepStr;
        
        // Agregar TODOS los tr (Instrucción + Materiales si los hay)
        while (tempDiv.firstChild) {
            container.appendChild(tempDiv.firstChild);
        }
        
        stepCount++;
        updateTheoreticalQuantities(); // Recalcular después de añadir
    }

    function removeStep(btn) {
        const mainRow = btn.closest('tr');
        const index = mainRow.dataset.stepIndex;
        
        Swal.fire({
            title: '¿Eliminar actividad?',
            text: "Esta actividad y sus registros asociados se borrarán.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000',
            confirmButtonText: 'Eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Eliminar la fila principal y sus ingredientes asociados (sub-rows)
                document.querySelectorAll(`[data-step-parent="${index}"]`).forEach(el => el.remove());
                mainRow.remove();
            }
        });
    }

    function toggleStepType(select, index) {
        const type = select.value;
        const mainRow = select.closest('tr');
        
        // 1. Builder de Carga e Ingredientes (Solo para CARGA)
        const builder = document.getElementById(`step-${index}-builder`);
        if (builder) {
            if (type === 'CARGA') builder.classList.remove('hidden');
            else builder.classList.add('hidden');
        }

        document.querySelectorAll(`[data-step-parent="${index}"]`).forEach(el => {
            if (type === 'CARGA') el.classList.remove('hidden');
            else el.classList.add('hidden');
        });

        // 2. Parámetros de Mezcla (Solo para MEZCLA)
        const paramActions = document.getElementById(`step-${index}-params-actions`);
        const paramContainer = document.getElementById(`step-${index}-params`);
        
        if (paramActions && paramContainer) {
            if (type === 'MEZCLA') {
                paramActions.classList.remove('hidden');
                paramContainer.classList.remove('hidden');
            } else {
                paramActions.classList.add('hidden');
                paramContainer.classList.add('hidden');
            }
        }

        // 3. Control en Proceso (IPC)
        const ipcContainer = document.getElementById(`step-${index}-ipc`);
        if (ipcContainer) {
            if (type === 'CONTROL_PROCESO') ipcContainer.classList.remove('hidden');
            else ipcContainer.classList.add('hidden');
        }

        if (type === 'CARGA') {
            updateTheoreticalQuantities();
            document.getElementById(`step-${index}-action-row`)?.classList.remove('hidden');
        } else {
            document.getElementById(`step-${index}-action-row`)?.classList.add('hidden');
        }
    }

    function toggleParameter(index, param) {
        const container = document.getElementById(`step-${index}-param-${param}`);
        const btn = event.currentTarget;
        const isHidden = container.classList.contains('hidden');
        
        if (isHidden) {
            container.classList.remove('hidden');
            btn.innerText = `- Quitar ${param === 'time' ? 'Tiempo' : 'Velocidad'}`;
            btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200');
        } else {
            container.classList.add('hidden');
            btn.innerText = `+ Agregar ${param === 'time' ? 'Tiempo' : 'Velocidad'}`;
            btn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-200');
            // Limpiar valor al ocultar
            const input = document.getElementById(`input-${param}-${index}`);
            if (input) input.value = '';
        }
    }

    function addIngredientRow(index, select) {
        const option = select.options[select.selectedIndex];
        const materialId = select.value;
        const materialName = option.dataset.name;
        const materialUnit = option.dataset.unit || 'KG';
        const materialPercentage = parseFloat(option.dataset.percentage) || 0;
        
        // Calcular cantidad inicial basada en el tamaño de lote maestro
        const batchSize = parseFloat(document.getElementById('master_batch_size_input').value) || 0;
        const initialQty = ((materialPercentage / 100) * batchSize).toFixed(2);

        // Crear la Fila (TR)
        const tr = document.createElement('tr');
        tr.className = "step-material-row border-b-2 border-black group/mat";
        tr.dataset.stepParent = index;
        
        tr.innerHTML = `
            <!-- ACTIVIDAD: Nombre + Delete -->
            <td class="border-2 border-black p-4 pl-10 text-start align-middle relative" style="width: 35%;">
                <input type="hidden" class="ing-id" value="${materialId}">
                <span class="font-bold text-gray-800 uppercase block truncate" style="font-size: 12px;" title="${materialName}">
                    • ${materialName}
                </span>
                <button type="button" onclick="this.closest('tr').remove()" 
                        class="absolute left-2 top-1/2 -translate-y-1/2 text-red-400 hover:text-red-600 font-bold text-[18px] opacity-0 group-hover/mat:opacity-100 transition-opacity">
                    ×
                </button>
            </td>

            <!-- UNIDAD (5%) -->
            <td class="border-2 border-black p-2 text-center align-middle" style="width: 5%;">
                <select class="ing-unit w-full bg-transparent border-none p-0 text-[13px] font-black text-center focus:ring-0">
                    <option value="KG" ${materialUnit === 'KG' ? 'selected' : ''}>KG</option>
                    <option value="L" ${materialUnit === 'L' ? 'selected' : ''}>L</option>
                </select>
            </td>

            <!-- TEÓRICA (10%) -->
            <td class="border-2 border-black p-2 text-center align-middle" style="width: 10%;">
                <input type="number" step="0.01" class="ing-quantity w-full bg-transparent border-none p-0 text-[13px] font-black text-center focus:ring-0" 
                       value="${initialQty}"
                       data-percentage="${materialPercentage}"
                       placeholder="0.00">
            </td>

            <!-- UTILIZADA (10%) -->
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;">---</td>

            <!-- REGISTROS VACÍOS -->
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 5%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 5%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;"></td>
            <td class="border-2 border-black p-4 text-center align-middle" style="width: 10%;"></td>
        `;

        // Insertar siempre AL FINAL de la lista de materiales (secuencial hacia abajo)
        const actionRow = document.getElementById(`step-${index}-action-row`);
        const materialRows = document.querySelectorAll(`tr.step-material-row[data-step-parent="${index}"]`);
        
        if (materialRows.length > 0) {
            materialRows[materialRows.length - 1].after(tr);
        } else if (actionRow) {
            actionRow.after(tr);
        } else {
            // Fallback
            const mainRow = document.querySelector(`tr.step-card-wrapper[data-step-index="${index}"]`);
            mainRow.after(tr);
        }
    }



    function updateTheoreticalQuantities() {
        const batchSizeInput = document.getElementById('master_batch_size_input');
        const batchSize = parseFloat(batchSizeInput.value) || 0;
        
        // Actualizar tabla de Fabricación (Sección 4)
        const batchDisplay = document.getElementById('fabrication-batch-size-display');
        if (batchDisplay) {
            batchDisplay.innerText = batchSize > 0 ? `${batchSize.toFixed(2)} KG` : '---';
        }

        // 1. Actualizar spans estáticos (.theoretical-quantity)
        document.querySelectorAll('.theoretical-quantity').forEach(span => {
            const percentage = parseFloat(span.dataset.percentage) || 0;
            const calculated = batchSize > 0 ? (percentage / 100) * batchSize : 0;
            span.innerText = calculated.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        });

        // 2. Actualizar inputs dinámicos (.ing-quantity)
        document.querySelectorAll('.ing-quantity').forEach(input => {
            const percentage = parseFloat(input.dataset.percentage) || 0;
            if (percentage > 0 && batchSize > 0) {
                const calculated = (percentage / 100) * batchSize;
                input.value = calculated.toFixed(2);
            }
        });
    }

    // Inicializar cálculos al cargar
    document.addEventListener('DOMContentLoaded', updateTheoreticalQuantities);
</script>
@endpush

@push('styles')
<style>
    input::placeholder, textarea::placeholder {
        color: #d1d5db;
        font-style: normal;
    }
    input:focus, textarea:focus {
        background-color: #f9fafb;
    }

    /* Forzar texto normal en áreas de contenido */
    .content-normal, 
    textarea.content-normal, 
    input.content-normal {
        font-style: normal !important;
        font-weight: 400 !important;
    }

    @media print {
        header, #sidebar, .hidden-print, nav, .flex.justify-end.gap-4 {
            display: none !important;
        }
        body { 
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .bg-white {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 2px solid black !important;
        }
        .tabla-auro, .tabla-auro td, .tabla-auro th {
            border: 2px solid black !important;
        }
        input, textarea {
            border: none !important;
            padding: 0 !important;
            background: transparent !important;
        }
    }
</style>
@endpush
@endsection
