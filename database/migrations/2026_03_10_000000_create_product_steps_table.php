<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('step_number');
            $table->enum('type', ['CARGA', 'MEZCLA', 'RENDIMIENTO', 'TEXTO'])->default('TEXTO');
            $table->text('description')->nullable();
            
            // For 'MEZCLA' steps
            $table->integer('theoretical_time_minutes')->nullable();
            $table->integer('target_rpm')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_steps');
    }
};
