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
        Schema::create('manufacturing_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
            
            // Step Identification
            $table->integer('step_number'); // Ej. 1, 2, 3, 4, 5
            $table->string('step_name'); // Ej. "Adición y Tamizado", "Mezcla"
            
            // Standard Time Tracking
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // Process Parameters based on Q-MUTIN
            $table->decimal('quantity_used', 12, 2)->nullable(); // Material used during this step
            $table->integer('rpm')->nullable(); // Machine speed for mixing
            $table->boolean('visual_check')->default(false); // Yes/No for visual cleanliness/mixture
            
            // e-Signatures CFR 21 Part 11
            $table->foreignId('realizado_por')->nullable()->constrained('users');
            $table->foreignId('verificado_por')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_records');
    }
};
