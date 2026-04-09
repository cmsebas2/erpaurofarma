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
        Schema::create('batch_packaging_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            
            // Sección 1: Calidad
            $table->boolean('color_conforme')->default(true);
            $table->boolean('odor_conforme')->default(true);
            $table->boolean('texture_conforme')->default(true);
            $table->boolean('particles_free')->default(true);
            
            // Sección 3: Pesos para Promedio (10 ítems)
            for ($i = 1; $i <= 10; $i++) {
                $table->decimal("weight_$i", 10, 3)->nullable();
            }
            $table->decimal('average_weight', 10, 3)->nullable();

            // Tiempo y Estado
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('status')->default('PENDIENTE'); // PENDIENTE, COMPLETADO

            // Firmas EBR
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('qa_user_id')->nullable()->constrained('users');
            $table->timestamp('qa_verified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_packaging_results');
    }
};
