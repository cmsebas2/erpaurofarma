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
        Schema::create('manufacturing_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->string('step_name'); // e.g. Mezclado
            $table->string('parameter_name'); // e.g. Tiempo, Velocidad
            $table->string('parameter_value'); 
            $table->foreignId('operario_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('verificador_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_instructions');
    }
};
