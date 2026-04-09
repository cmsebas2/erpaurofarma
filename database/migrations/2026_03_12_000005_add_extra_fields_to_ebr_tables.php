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
        Schema::table('plan_step_ingredients', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('formula_ingredient_id');
            $table->decimal('theoretical_quantity', 12, 4)->nullable()->after('unit');
        });

        Schema::table('product_manufacturing_plans', function (Blueprint $table) {
            $table->decimal('base_batch_size', 12, 4)->nullable()->after('issue_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_step_ingredients', function (Blueprint $table) {
            $table->dropColumn(['unit', 'theoretical_quantity']);
        });

        Schema::table('product_manufacturing_plans', function (Blueprint $table) {
            $table->dropColumn('base_batch_size');
        });
    }
};
