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
        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_step_ingredient_id')->nullable()->after('plan_step_id');
            $table->foreign('plan_step_ingredient_id')->references('id')->on('plan_step_ingredients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->dropForeign(['plan_step_ingredient_id']);
            $table->dropColumn('plan_step_ingredient_id');
        });
    }
};
