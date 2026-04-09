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
        Schema::create('product_step_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_step_id')->constrained('product_steps')->onDelete('cascade');
            $table->foreignId('formula_ingredient_id')->constrained('formula_ingredients')->onDelete('cascade');
            
            // Percentage of the specific formula ingredient to add in this step (e.g. 50% of the carrier)
            $table->decimal('percentage_allocation', 8, 2)->default(100.00);
            
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
        Schema::dropIfExists('product_step_ingredients');
    }
};
