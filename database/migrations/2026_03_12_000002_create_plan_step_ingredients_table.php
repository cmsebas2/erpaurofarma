<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_step_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_step_id')->constrained()->onDelete('cascade');
            $table->foreignId('formula_ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('percentage_allocation', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_step_ingredients');
    }
};
