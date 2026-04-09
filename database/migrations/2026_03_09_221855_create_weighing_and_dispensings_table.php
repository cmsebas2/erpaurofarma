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
        Schema::create('weighing_and_dispensings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->foreignId('formula_ingredient_id')->constrained('formula_ingredients')->onDelete('restrict');
            $table->string('lot_mp'); // Lote materia prima
            $table->decimal('theoretical_weight', 12, 4);
            $table->decimal('actual_weight', 12, 4);
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('restrict');
            $table->foreignId('operario_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('verificador_id')->constrained('users')->onDelete('restrict');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weighing_and_dispensings');
    }
};
