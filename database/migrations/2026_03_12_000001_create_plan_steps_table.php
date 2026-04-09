<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('product_manufacturing_plans')->onDelete('cascade');
            $table->integer('step_number');
            $table->enum('type', ['CARGA', 'MEZCLA', 'TAMIZADO', 'MUESTREO', 'RENDIMIENTO', 'TEXTO']);
            $table->text('description');
            $table->integer('theoretical_time_minutes')->nullable();
            $table->integer('target_rpm')->nullable();
            $table->string('mesh_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_steps');
    }
};
