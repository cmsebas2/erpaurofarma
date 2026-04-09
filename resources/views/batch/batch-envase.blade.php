@extends('layouts.app')

@section('header_title', 'Formatos - Envase (A3PPR0010)')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .tabla-auro { width: 100%; border-collapse: collapse; border: 2px solid black; }
    .tabla-auro td, .tabla-auro th { border: 2px solid black; padding: 8px; color: black !important; font-size: 13px; }
    .bg-auro-header { background-color: #f8fafc; }
    .fw-bold { font-weight: 700 !important; }
    .btn-outline-sign {
        border: 2px solid black; background: transparent; color: black; font-weight: 800; padding: 6px 20px; text-transform: uppercase; border-radius: 4px; transition: all 0.2s;
    }
    .btn-outline-sign:hover { background: black; color: white; }
    .readonly-bg { background-color: #e5e7eb !important; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    
    <!-- Progress Indicator -->
    @include('batch.partials.ebr-navigation')

    @php
        $res = $op->packagingResult;
        $isSigned = $res && $res->signed_at;
        $isQaSigned = $res && $res->qa_verified_at;
        $readonly = $isSampleSigned ?? $isSigned ? 'readonly' : '';
        $disabled = $isSigned ? 'disabled' : '';
        $bgClass = $isSigned ? 'readonly-bg' : 'bg-white';
        
        // Peso Declarado (Basado en la primera presentación para el cálculo de límites)
        // El usuario pidió: "Calcule el 'Peso Óptimo' y 'Superior' basándose en el 'Peso Declarado'"
        $presentacion = $op->opPresentations->first()->presentation->name ?? '0';
        preg_match('/(\d+(?:\.\d+)?)/', $presentacion, $matches);
        $pesoDeclarado = isset($matches[1]) ? floatval($matches[1]) : 0;
        
        // Límites Sugeridos (Ejemplo: +/- 1%)
        $optimo = $pesoDeclarado;
        $superior = $optimo * 1.02;
        $inferior = $optimo * 0.98;
    @endphp

    <div class="bg-white p-8 shadow-2xl border-2 border-black min-h-screen font-sans text-gray-900 mb-10" id="envase-container">
        
        <!-- ENCABEZADO NORMATIVO -->
        <table class="tabla-auro mb-6">
            <tbody>
                <tr>
                    <td style="width: 20%;" class="text-center font-bold">A3PPR0010</td>
                    <td rowspan="2" style="width: 60%;" class="text-center text-xl font-black uppercase">CONTROL DE ENVASE Y PESOS</td>
                    <td rowspan="2" style="width: 20%;" class="text-center">
                        <img src="{{ asset('img/logo.png') }}" alt="AUROFARMA" style="max-height: 50px;" class="mx-auto grayscale opacity-80">
                    </td>
                </tr>
                <tr>
                    <td class="text-center text-[10px]">VERSIÓN: 01</td>
                </tr>
            </tbody>
        </table>

        <!-- PUNTO 2: INFORMACIÓN GENERAL -->
        <div class="border-2 border-black mb-6">
            <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">2. INFORMACIÓN DEL LOTE</div>
            <div class="grid grid-cols-2 p-4 gap-y-4">
                <div class="flex flex-col">
                    <span class="text-[10px] items-center font-bold uppercase text-gray-500">Producto:</span>
                    <span class="text-lg font-black text-blue-900">{{ $op->product->name }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Lote:</span>
                    <span class="text-lg font-black">{{ $op->lote }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Tamaño del Lote:</span>
                    <span class="text-lg font-black">{{ number_format($op->bulk_size_kg, 2) }} {{ $op->unit }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Cantidad a Envasar:</span>
                    <span class="text-lg font-black text-green-700">
                        {{ number_format($op->opPresentations->sum('units_to_produce'), 0) }} UNIDADES
                    </span>
                </div>
                <div class="flex flex-col border-t pt-2">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Peso Declarado (g):</span>
                    <span id="peso-declarado-val" class="text-lg font-black">{{ $pesoDeclarado }} g</span>
                </div>
                <div class="flex flex-col border-t pt-2">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Peso Promedio Real (g):</span>
                    <span id="peso-promedio-header" class="text-2xl font-black text-red-600">
                        {{ number_format($res->average_weight ?? 0, 2) }} g
                    </span>
                </div>
            </div>
            <div class="flex justify-between border-t-2 border-black px-4 py-2 bg-gray-50 font-bold text-[11px]">
                <span>INICIO: {{ $res->start_time ? $res->start_time->format('d/m/Y H:i') : '---' }}</span>
                <span>FINAL: <span id="envase-end-time-display">{{ $res->end_time ? $res->end_time->format('d/m/Y H:i') : '---' }}</span></span>
            </div>
        </div>

        <!-- PUNTO 3: CARACTERÍSTICAS FÍSICAS (CALIDAD) -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="border-2 border-black">
                <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">3.1 CARACTERÍSTICAS SENSORIALES</div>
                <div class="p-4 space-y-4">
                    @foreach(['color' => 'Color conforme', 'odor' => 'Olor característico', 'texture' => 'Textura uniforme', 'particles' => 'Libre de partículas'] as $key => $label)
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-[12px] uppercase">{{ $label }}</span>
                            <select id="check-{{ $key }}" {{ $disabled }} class="border-2 border-black p-1 text-xs font-bold {{ $bgClass }}">
                                @php $field = $key . ($key == 'particles' ? '_free' : '_conforme') @endphp
                                <option value="1" {{ ($res && $res->$field) ? 'selected' : '' }}>SÍ</option>
                                <option value="0" {{ ($res && !$res->$field) ? 'selected' : '' }}>NO</option>
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- PESO PROMEDIO (PUNTO 3) -->
            <div class="border-2 border-black">
                <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">3.2 CÁLCULO DE PESO PROMEDIO (n=10)</div>
                <div class="p-4">
                    <div class="grid grid-cols-5 gap-2 mb-4">
                        @for($i = 1; $i <= 10; $i++)
                            <div class="flex flex-col">
                                <span class="text-[9px] font-bold text-center">#{{ $i }}</span>
                                <input type="number" step="0.1" id="weight-{{ $i }}" value="{{ $res->{'weight_'.$i} ?? '' }}" {{ $readonly }}
                                       class="weight-avg-input border border-black p-1 text-center text-xs font-bold {{ $bgClass }}">
                            </div>
                        @endfor
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 p-2 text-center rounded">
                        <span class="text-[10px] font-bold text-yellow-800 uppercase block">Promedio Calculado</span>
                        <span id="peso-promedio-calc" class="text-xl font-black">{{ number_format($res->average_weight ?? 0, 2) }}</span> <span class="font-bold">g</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- PUNTO 4: CONTROL DE PESOS EN PROCESO (Gráfico) -->
        <div class="border-2 border-black mb-6">
            <div class="bg-black text-white p-1 text-[10px] font-black uppercase tracking-widest pl-2">4. REGISTRO DE PESOS EN PROCESO (SPC)</div>
            <div class="grid grid-cols-3 gap-0 h-[350px]">
                <div class="col-span-1 border-r-2 border-black overflow-y-auto p-2">
                    <table class="w-full text-center text-[10px]">
                        <thead>
                            <tr class="bg-gray-100 font-bold uppercase border-b border-black">
                                <th class="p-1">Hora</th>
                                <th class="p-1">Peso (g)</th>
                                <th class="p-1"></th>
                            </tr>
                        </thead>
                        <tbody id="periodic-weights-body">
                            @foreach($op->packagingWeightControls as $c)
                                <tr class="border-b border-gray-200">
                                    <td class="p-1 font-mono">{{ $c->controlled_at->format('H:i') }}</td>
                                    <td class="p-1 font-bold">{{ number_format($c->weight, 1) }} g</td>
                                    <td class="p-1">✓</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(!$isSigned)
                    <div class="mt-4 flex gap-1">
                        <input type="number" step="0.1" id="new-weight-val" class="flex-1 border-2 border-black p-1 text-xs font-bold" placeholder="0.0">
                        <button onclick="addPeriodicWeight()" class="bg-black text-white px-3 py-1 text-[10px] font-black uppercase">Añadir</button>
                    </div>
                    @endif
                </div>
                <!-- CHART CONTAINER -->
                <div class="col-span-2 p-4 bg-gray-50 flex flex-col items-center justify-center">
                    <canvas id="weightChart" style="width: 100%; height: 100%; max-height: 280px;"></canvas>
                    <div class="flex gap-4 mt-2 text-[8px] font-bold uppercase">
                        <div class="flex items-center gap-1"><span class="w-2 h-2 bg-red-500 rounded-full"></span> L. Superior ({{ number_format($superior,2) }})</div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span> L. Óptimo ({{ number_format($optimo,2) }})</div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 bg-red-500 rounded-full"></span> L. Inferior ({{ number_format($inferior,2) }})</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FIRMAS -->
        <div class="grid grid-cols-2 gap-4 mt-8">
            <!-- REALIZÓ -->
            <div class="border-2 border-black p-4 text-center">
                <span class="block text-[10px] font-black uppercase mb-4 text-gray-500">Realizado por:</span>
                <div id="sign-operator-container">
                    @if($isSigned)
                        <div class="flex flex-col items-center leading-tight">
                            <div class="text-[10px] font-black uppercase text-green-600 tracking-widest mb-1">✓ FIRMADO</div>
                            <div class="text-[12px] font-black uppercase">{{ $res->user->name }}</div>
                            <div class="text-[10px] text-gray-500 font-mono italic">{{ $res->signed_at->format('d/m/Y H:i') }}</div>
                        </div>
                    @else
                        <button type="button" onclick="signPackaging()" class="btn-outline-sign">Firmar Cierre</button>
                    @endif
                </div>
            </div>
            <!-- VERIFICÓ -->
            <div class="border-2 border-black p-4 text-center">
                <span class="block text-[10px] font-black uppercase mb-4 text-gray-500">Verificado por:</span>
                <div id="sign-qa-container">
                    @if($isQaSigned)
                        <div class="flex flex-col items-center leading-tight">
                            <div class="text-[10px] font-black uppercase text-blue-600 tracking-widest mb-1">✓ FIRMADO</div>
                            <div class="text-[12px] font-black uppercase">{{ $res->qaUser->name }}</div>
                            <div class="text-[10px] text-gray-500 font-mono italic">{{ $res->qa_verified_at->format('d/m/Y H:i') }}</div>
                        </div>
                    @elseif($isSigned)
                        <button type="button" onclick="qaVerifyEnvase()" class="btn-outline-sign border-blue-600 text-blue-600 hover:bg-blue-600">Verificar Calidad</button>
                    @else
                         <span class="text-[10px] italic text-gray-400">Esperando firma de operario...</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-black transition-all">← Volver al Dashboard</a>
        </div>
    </div>
</div>

<!-- Modal QA Verification Reuse -->
@include('batch.modals.qa-verification')

@endsection

@push('scripts')
<script>
    const WEIGHT_DECLARADO = {{ $pesoDeclarado }};
    const OPTIMO = {{ $optimo }};
    const SUPERIOR = {{ $superior }};
    const INFERIOR = {{ $inferior }};
    
    // CHART LOGIC
    let chart;
    const initialLabels = @json($op->packagingWeightControls->map(fn($c) => $c->controlled_at->format('H:i')));
    const initialData = @json($op->packagingWeightControls->pluck('weight'));

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('weightChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialLabels,
                datasets: [
                    {
                        label: 'Peso Real',
                        data: initialData,
                        borderColor: '#2563eb', // Blue
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#2563eb',
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Superior',
                        data: Array(initialLabels.length || 1).fill(SUPERIOR),
                        borderColor: '#ef4444',
                        borderWidth: 1.5,
                        orderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Óptimo',
                        data: Array(initialLabels.length || 1).fill(OPTIMO),
                        borderColor: '#22c55e',
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Inferior',
                        data: Array(initialLabels.length || 1).fill(INFERIOR),
                        borderColor: '#ef4444',
                        borderWidth: 1.5,
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        suggestedMin: INFERIOR - 2,
                        suggestedMax: SUPERIOR + 2,
                        ticks: { font: { size: 9 } }
                    },
                    x: { ticks: { font: { size: 8 } } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // AVERAGE LOGIC
        const weightInputs = document.querySelectorAll('.weight-avg-input');
        weightInputs.forEach(input => {
            input.addEventListener('input', calculateAverage);
        });
    });

    function calculateAverage() {
        const inputs = document.querySelectorAll('.weight-avg-input');
        let sum = 0;
        let count = 0;
        inputs.forEach(i => {
            const val = parseFloat(i.value);
            if (!isNaN(val)) { sum += val; count++; }
        });
        const avg = count > 0 ? (sum / count) : 0;
        const avgFormatted = avg.toFixed(2);
        
        document.getElementById('peso-promedio-calc').innerText = avgFormatted;
        document.getElementById('peso-promedio-header').innerText = avgFormatted + ' g';
        
        // Visual warning if out of bounds
        if (avg > SUPERIOR || avg < INFERIOR) {
            document.getElementById('peso-promedio-header').className = 'text-2xl font-black text-red-600 animate-pulse';
        } else {
            document.getElementById('peso-promedio-header').className = 'text-2xl font-black text-green-600';
        }
    }

    function addPeriodicWeight() {
        const valInput = document.getElementById('new-weight-val');
        const weight = parseFloat(valInput.value);
        if (isNaN(weight)) return;

        fetch("{{ route('batch.envase.weight.store', $op) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ weight: weight })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update Table
                const tbody = document.getElementById('periodic-weights-body');
                const row = `<tr class="border-b border-gray-200">
                                <td class="p-1 font-mono">${data.time}</td>
                                <td class="p-1 font-bold">${data.weight} g</td>
                                <td class="p-1">✓</td>
                             </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
                
                // Update Chart
                chart.data.labels.push(data.time);
                chart.data.datasets[0].data.push(data.weight);
                // Update limit lines length
                chart.data.datasets[1].data.push(SUPERIOR);
                chart.data.datasets[2].data.push(OPTIMO);
                chart.data.datasets[3].data.push(INFERIOR);
                
                chart.update();
                valInput.value = '';
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    }

    function signPackaging() {
        const data = {
            color_conforme: document.getElementById('check-color').value,
            odor_conforme: document.getElementById('check-odor').value,
            texture_conforme: document.getElementById('check-texture').value,
            particles_free: document.getElementById('check-particles').value,
            average_weight: document.getElementById('peso-promedio-calc').innerText
        };
        
        // Add individual weights
        for(let i=1; i<=10; i++) {
            data['weight_' + i] = document.getElementById('weight-' + i).value;
        }

        Swal.fire({
            title: 'Cerrar Envase',
            text: '¿Confirma que todos los controles de peso y sensoriales son correctos?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, Firmar',
            heightAuto: false
        }).then(result => {
            if (result.isConfirmed) {
                fetch("{{ route('batch.envase.store', $op) }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Simple reload to apply all readonly logic
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    function qaVerifyEnvase() {
        // Reuse the validator in the modal
        openQaModal('Envase y Pesos');
        window.finishQaVerificationSave = function() {
            const formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('qa_user_id', document.getElementById('qa-user-id-hidden')?.value || 1); // Mock or get from modal

            // The modal submitQaVerification already does the fetching if implemented correctly
            // But here I'll override the logic to target the Envase verification route
            const email = document.getElementById('qa-email').value;
            const password = document.getElementById('qa-password').value;

            fetch("{{ route('batch.qa.credentials', $op) }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ email: email, password: password })
            })
            .then(res => res.json())
            .then(auth => {
                if(auth.success) {
                    fetch("{{ route('batch.envase.verify', $op) }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ qa_user_id: auth.user_id })
                    })
                    .then(r => r.json())
                    .then(v => {
                        if(v.success) { location.reload(); }
                    });
                } else {
                    Swal.fire('Error', auth.message, 'error');
                }
            });
        };
    }
</script>
@endpush
