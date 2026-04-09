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
        Schema::create('manufacturing_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_step_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('step_type');
            
            // Dynamic payload
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('rpm')->nullable();
            $table->decimal('yield_kg', 10, 2)->nullable();
            $table->text('observations')->nullable();
            
            // Signatures
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamp('signed_at');
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
        Schema::dropIfExists('manufacturing_executions');
    }
};
