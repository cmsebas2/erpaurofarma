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
        Schema::create('dispensings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->foreignId('realizado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_realizado')->nullable();
            $table->foreignId('verificado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_verificado')->nullable();
            $table->string('status')->default('EN PROCESO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispensings');
    }
};
