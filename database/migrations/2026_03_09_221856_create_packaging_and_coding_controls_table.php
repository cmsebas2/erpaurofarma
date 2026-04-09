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
        Schema::create('packaging_and_coding_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->string('packaging_material');
            $table->decimal('quantity_received', 12, 4);
            $table->decimal('quantity_used', 12, 4);
            $table->decimal('quantity_returned', 12, 4)->default(0);
            $table->decimal('quantity_damaged', 12, 4)->default(0);
            $table->string('status')->default('CONFORME'); 
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
        Schema::dropIfExists('packaging_and_coding_controls');
    }
};
