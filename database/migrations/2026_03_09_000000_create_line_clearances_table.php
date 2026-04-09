<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('line_clearances', function (Blueprint $output) {
            $output->id();
            $output->foreignId('production_order_id')->constrained()->onDelete('cascade');
            $output->string('area'); // 'Dispensación', 'Fabricación', 'Envasado', 'Acondicionado'
            $output->date('fecha_inicio');
            $output->time('hora_inicio');
            $output->string('producto_anterior');
            $output->string('lote_anterior');
            $output->json('respuestas_checklist');
            $output->string('diferencial_presion')->nullable();
            $output->date('fecha_fin')->nullable();
            $output->time('hora_fin')->nullable();
            $output->foreignId('realizado_por')->constrained('users');
            $output->foreignId('verificado_por')->nullable()->constrained('users');
            $output->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_clearances');
    }
};
