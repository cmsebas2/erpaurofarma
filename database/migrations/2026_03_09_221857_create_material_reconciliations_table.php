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
        Schema::create('material_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->decimal('total_theoretical', 12, 4);
            $table->decimal('total_actual', 12, 4);
            $table->decimal('yield_percent', 6, 2);
            $table->string('status')->default('CONFORME'); // CONFORME o DESVIACION
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_reconciliations');
    }
};
