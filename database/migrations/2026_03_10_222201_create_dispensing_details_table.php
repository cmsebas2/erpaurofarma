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
        Schema::create('dispensing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispensing_id')->constrained()->onDelete('cascade');
            $table->foreignId('formula_ingredient_id')->constrained()->onDelete('cascade');
            $table->date('fecha')->nullable();
            $table->decimal('cantidad_teorica', 12, 2)->nullable();
            $table->decimal('cantidad_real', 12, 2)->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_final')->nullable();
            $table->foreignId('realizado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verificado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispensing_details');
    }
};
