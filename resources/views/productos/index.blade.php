@extends('layouts.app')

@section('header_title', 'Catálogo de Productos')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header Actions -->
    <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Catálogo de Productos</h2>
            <p class="text-sm text-gray-500">Listado interactivo de productos autorizados</p>
        </div>
        <div>
            <a href="{{ route('productos.create') }}" class="bg-aurofarma-blue hover:bg-blue-800 text-white text-sm font-bold py-2 px-4 rounded-lg shadow transition-colors flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Crear Producto
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach($products as $product)
        <div class="group relative flex flex-col bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <!-- Botón Eliminar (Flotante) -->
            <form action="{{ route('productos.destroy', $product['id']) }}" method="POST" class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('¿Estás seguro de eliminar este producto y TODA su fórmula? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-colors border border-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>

            <a href="{{ route('productos.show', $product['id']) }}" class="flex-1 flex flex-col">
                <!-- Zona de Imagen -->
                <div class="h-40 w-full bg-gray-50 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('img/productos/' . $product['image']) }}" 
                         alt="{{ $product['name'] }}"
                         class="w-full h-full object-contain p-4 transition-transform duration-300 group-hover:scale-110"
                         onerror="this.onerror=null; this.outerHTML='<svg class=\'w-16 h-16 text-gray-300\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z\'></path></svg>';">
                </div>
                <!-- Zona de Texto -->
                <div class="p-4 flex-1 flex flex-col items-center justify-center text-center transition-colors duration-300 group-hover:bg-aurofarma-blue group-hover:text-white">
                    <h3 class="text-sm font-bold uppercase tracking-wide leading-snug">{{ $product['name'] }}</h3>
                    <div class="mt-2 px-2 py-0.5 bg-gray-100 text-gray-500 text-[9px] font-bold rounded uppercase tracking-tighter group-hover:bg-white/20 group-hover:text-white transition-colors">
                        Registro ICA: {{ $product['ica_license'] ?? 'N/A' }}
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
