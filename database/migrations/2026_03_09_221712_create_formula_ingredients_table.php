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
        Schema::create('formula_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // ID de presentación opcional (nulo para granel, asignado para material de empaque)
            $table->foreignId('presentation_id')->nullable()->constrained('product_presentations')->onDelete('cascade');
            $table->string('material_code');
            $table->string('material_name');
            $table->string('material_type'); // EJ: MATERIA PRIMA, MATERIAL DE EMPAQUE
            $table->string('function')->nullable();
            $table->string('unit');
            $table->decimal('percentage', 8, 4);
            $table->decimal('quantity', 12, 4)->nullable(); // Cantidad calculada o absoluta según unidad
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formula_ingredients');
    }
};
