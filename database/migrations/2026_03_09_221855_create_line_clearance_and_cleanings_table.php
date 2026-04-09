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
        Schema::create('line_clearance_and_cleanings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->string('area_name');
            $table->foreignId('equipment_id')->nullable()->constrained('equipment')->onDelete('restrict');
            $table->string('cleaning_status'); // Limpio, Por limpiar
            $table->string('line_clearance_status'); // Aprobado, Rechazado
            $table->foreignId('operario_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('verificador_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_clearance_and_cleanings');
    }
};
