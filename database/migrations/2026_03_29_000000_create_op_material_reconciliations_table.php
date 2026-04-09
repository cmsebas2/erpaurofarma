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
        Schema::create('op_material_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->enum('type', ['Materia Prima', 'Material de Empaque']);
            $table->string('description');
            $table->string('unit');
            $table->string('batch_number')->nullable();
            $table->decimal('received_qty', 10, 4)->nullable();
            $table->decimal('used_qty', 10, 4)->nullable();
            $table->decimal('returned_qty', 10, 4)->nullable();
            
            // Audit Trail
            $table->dateTime('date')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users');
            $table->dateTime('signed_at')->nullable();
            $table->foreignId('qa_user_id')->nullable()->constrained('users');
            $table->dateTime('qa_verified_at')->nullable();
            
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('op_material_reconciliations');
    }
};
