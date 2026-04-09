@extends('layouts.app')

@section('header_title', 'Electronic Batch Record - Iniciar OP')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    
    @include('batch.partials.ebr-navigation')

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-5 border-b border-gray-200 flex items-center">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-aurofarma-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-lg leading-6 font-bold text-white">Apertura de Orden de Producción</h3>
                <p class="mt-1 text-sm text-gray-300">Ingrese los datos para inicializar el Lote en el sistema.</p>
            </div>
        </div>
        
        <div class="p-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('batch.store') }}" method="POST" class="space-y-8" id="op-form">
                @csrf
                
                <!-- SECCIÓN 1: DATOS GENERALES -->
                <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                    <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center mr-3 text-slate-600">1</span>
                        DATOS GENERALES DE LA ORDEN
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- # de OP -->
                        <div class="lg:col-span-1">
                            <label for="op_number" class="block text-sm font-bold text-gray-700 mb-1"># de OP <span class="text-red-500">*</span></label>
                            <input type="text" name="op_number" id="op_number" required class="focus:ring-aurofarma-blue focus:border-aurofarma-blue block w-full py-2.5 px-3 sm:text-sm border-gray-300 rounded-lg bg-white border font-mono font-medium text-gray-900 shadow-sm" placeholder="Ej. OP-1234">
                        </div>

                        <!-- Producto -->
                        <div class="lg:col-span-2">
                            <label for="product_id" class="block text-sm font-bold text-gray-700 mb-1">Producto <span class="text-red-500">*</span></label>
                            <select id="product_id" name="product_id" required class="mt-1 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-aurofarma-blue focus:border-aurofarma-blue sm:text-sm rounded-lg shadow-sm bg-white border">
                                <option value="" disabled selected>Seleccione un producto...</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                            data-vigencia="{{ $producto->vigencia_meses ?? 0 }}"
                                            data-presentations='@json($producto->presentations)'>
                                        {{ $producto->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lote -->
                        <div>
                            <label for="lote" class="block text-sm font-bold text-gray-700 mb-1">Lote Físico <span class="text-red-500">*</span></label>
                            <input type="text" name="lote" id="lote" required class="focus:ring-aurofarma-blue focus:border-aurofarma-blue block w-full py-2.5 px-3 sm:text-sm border-gray-300 rounded-lg bg-white border font-mono font-black text-slate-800 shadow-sm" placeholder="Ej. L2024001">
                        </div>

                        <!-- Maquilador -->
                        <div>
                            <label for="maquilador" class="block text-sm font-bold text-gray-700 mb-1">Maquilador</label>
                            <input type="text" name="maquilador" id="maquilador" readonly value="LABORATORIOS AUROFARMA" class="block w-full py-2.5 px-3 sm:text-sm border-gray-200 rounded-lg bg-blue-50 text-aurofarma-blue font-bold tracking-wide cursor-not-allowed text-center">
                        </div>

                        <!-- Fecha Fabricación -->
                        <div>
                            <label for="manufacture_date" class="block text-sm font-bold text-gray-700 mb-1">Fecha Fabricación</label>
                            <input type="text" name="manufacture_date" id="manufacture_date" readonly class="block w-full py-2.5 px-3 sm:text-sm border-gray-300 rounded-lg bg-slate-50 font-black text-slate-900 shadow-sm text-center cursor-not-allowed" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Fecha Vencimiento -->
                        <div>
                            <label for="expiration_date" class="block text-sm font-bold text-gray-700 mb-1 text-orange-700">Fecha Vencimiento (Calculado)</label>
                            <input type="text" name="expiration_date" id="expiration_date" readonly placeholder="YYYY-MM" class="block w-full py-2.5 px-3 sm:text-sm border-orange-200 rounded-lg bg-orange-50 font-black text-orange-900 cursor-not-allowed text-center">
                        </div>
                        <!-- Fecha Destrucción -->
                        <div>
                            <label for="destruction_date" class="block text-sm font-bold text-gray-700 mb-1 text-red-700">Fecha Destrucción (Vto + 1 año)</label>
                            <input type="text" name="destruction_date" id="destruction_date" readonly placeholder="YYYY-MM" class="block w-full py-2.5 px-3 sm:text-sm border-red-200 rounded-lg bg-red-50 font-black text-red-900 cursor-not-allowed text-center">
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: PLAN DE ENVASADO (DINÁMICO) -->
                <div class="bg-white p-6 rounded-xl border-2 border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center mr-3 text-slate-600">2</span>
                            PLAN DE ENVASADO
                        </h4>
                        <button type="button" id="btn-add-presentation" class="inline-flex items-center px-4 py-2 border border-blue-600 rounded-lg shadow-sm text-sm font-bold text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Agregar Presentación
                        </button>
                    </div>

                    <div id="presentations-container" class="space-y-4">
                        <!-- Aquí se inyectan las filas dinámicamente -->
                        <div class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-100 rounded-xl" id="empty-state">
                            No se han agredado presentaciones para envasar. Presione "+ Agregar Presentación" para comenzar.
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: TOTAL A GRANEL -->
                <div class="bg-slate-900 p-8 rounded-2xl shadow-2xl border-4 border-slate-800 text-center transform hover:scale-[1.01] transition-transform">
                    <span class="text-xs font-black text-aurofarma-teal uppercase tracking-[0.2em] mb-3 block">Requerimiento de Planta</span>
                    <h3 class="text-white text-lg font-bold mb-2">TAMAÑO TOTAL DEL LOTE A GRANEL</h3>
                    <div class="flex items-center justify-center space-x-4">
                        <input type="text" name="bulk_size_kg" id="bulk_size_kg" readonly 
                               class="bg-transparent text-center text-6xl font-black text-white w-2/3 border-none focus:ring-0 p-0" 
                               value="0.00">
                        <span class="text-aurofarma-teal text-4xl font-black">KG / L</span>
                    </div>
                    <p class="text-slate-400 text-sm mt-4">Este valor se autocalcula sumando el requerimiento de todas las presentaciones anteriores.</p>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button" onclick="window.history.back()" class="px-8 py-3 bg-white border border-slate-300 rounded-xl text-slate-700 font-bold hover:bg-slate-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-10 py-3 bg-aurofarma-blue rounded-xl text-white font-black shadow-lg shadow-blue-200 hover:opacity-90 active:scale-95 transition-all flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        APERTURAR ORDEN (OP)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const container = document.getElementById('presentations-container');
        const btnAdd = document.getElementById('btn-add-presentation');
        const bulkTotalInput = document.getElementById('bulk_size_kg');
        const emptyState = document.getElementById('empty-state');
        
        const mfgDateInput = document.getElementById('manufacture_date');
        const expDateInput = document.getElementById('expiration_date');
        const destDateInput = document.getElementById('destruction_date');

        let presentationsData = [];
        let rowCount = 0;

        // --- LÓGICA DE FECHAS (FORMATO: FAB: YYYY-MM-DD | VTO: YYYY-MM) ---
        function updateDates() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;

            const vigencia = parseInt(selectedOption.getAttribute('data-vigencia')) || 0;
            const mfgDateStr = mfgDateInput.value; // YYYY-MM-DD
            
            if (mfgDateStr && vigencia > 0) {
                // Parsear YYYY-MM-DD
                const parts = mfgDateStr.split('-');
                if (parts.length !== 3) return;
                
                let year = parseInt(parts[0]);
                let month = parseInt(parts[1]);
                let day = parseInt(parts[2]);

                // Sumar meses de vigencia para el Vencimiento
                month += vigencia;
                while (month > 12) {
                    month -= 12;
                    year += 1;
                }
                
                // Formato final Vencimiento: YYYY-MM
                const expDate = `${year}-${String(month).padStart(2, '0')}`;
                expDateInput.value = expDate;

                // Destrucción = Vto + 12 meses -> YYYY-MM
                const destDate = `${year + 1}-${String(month).padStart(2, '0')}`;
                destDateInput.value = destDate;
            }
        }

        // Forzar cálculo inicial si ya hay un producto (ej. por errores de validación)
        if (productSelect.value) {
            updateDates();
        }

        // --- LÓGICA DE FILAS DINÁMICAS ---
        productSelect.addEventListener('change', function() {
            const dataStr = this.options[this.selectedIndex].getAttribute('data-presentations');
            presentationsData = JSON.parse(dataStr || '[]');
            
            // Si cambia el producto, reseteamos las presentaciones agregadas
            container.innerHTML = '';
            container.appendChild(emptyState);
            emptyState.style.display = 'block';
            rowCount = 0;
            calculateGrandTotal();
            updateDates();
        });

        btnAdd.addEventListener('click', function() {
            if (!productSelect.value) {
                alert('Por favor seleccione un producto primero.');
                return;
            }

            if (rowCount >= 3) {
                alert('El sistema permite un máximo de 3 presentaciones por OP simultánea.');
                return;
            }

            emptyState.style.display = 'none';
            
            const rowIndex = rowCount;
            const rowHtml = `
                <div class="presentation-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-slate-200 rounded-xl bg-slate-50 relative animate-in fade-in slide-in-from-right duration-300">
                    <button type="button" class="btn-remove absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">×</button>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-500 uppercase mb-1">Presentación</label>
                        <select name="presentations[${rowIndex}][id]" required class="pres-select w-full border-gray-300 rounded-lg text-sm bg-white shadow-inner">
                            <option value="">-- Seleccionar --</option>
                            ${presentationsData.map(p => {
                                // Extraer peso del nombre (Ej "Saco 25 KG" -> 25)
                                let peso = 0;
                                const match = p.name.match(/(\d+(?:\.\d+)?)/);
                                if(match) peso = parseFloat(match[1]);
                                return `<option value="${p.id}" data-peso="${peso}">${p.name}</option>`;
                            }).join('')}
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-1">Unidades</label>
                        <input type="number" name="presentations[${rowIndex}][units]" required min="1" step="1" 
                               class="units-input w-full border-gray-300 rounded-lg text-sm font-bold text-center focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-1">Kilos Req.</label>
                        <input type="text" readonly name="presentations[${rowIndex}][total_row_kg]" 
                               class="row-kg w-full border-gray-100 rounded-lg text-sm bg-slate-100 font-black text-slate-700 text-center cursor-not-allowed" value="0.00">
                    </div>
                </div>
            `;
            
            const div = document.createElement('div');
            div.innerHTML = rowHtml;
            container.appendChild(div.firstElementChild);
            rowCount++;

            attachRowListeners(container.lastElementChild);
        });

        function attachRowListeners(row) {
            const select = row.querySelector('.pres-select');
            const input = row.querySelector('.units-input');
            const totalRow = row.querySelector('.row-kg');
            const btnRemove = row.querySelector('.btn-remove');

            const calculateRow = () => {
                const peso = parseFloat(select.options[select.selectedIndex]?.getAttribute('data-peso') || 0);
                const units = parseInt(input.value || 0);
                const total = peso * units;
                totalRow.value = total.toFixed(2);
                calculateGrandTotal();
            };

            select.addEventListener('change', calculateRow);
            input.addEventListener('input', calculateRow);
            
            btnRemove.addEventListener('click', function() {
                row.remove();
                rowCount--;
                if(rowCount == 0) emptyState.style.display = 'block';
                calculateGrandTotal();
            });
        }

        // Forzar pre-llenado de fecha actual si está vacía
        if (!mfgDateInput.value) {
            const now = new Date();
            const currentYearMonth = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
            mfgDateInput.value = currentYearMonth;
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.row-kg').forEach(el => {
                grandTotal += parseFloat(el.value || 0);
            });
            bulkTotalInput.value = grandTotal.toFixed(2);
        }
    });
</script>
@endpush
@endsection
