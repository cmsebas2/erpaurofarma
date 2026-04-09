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
            // Drop old FK
            $table->dropForeign(['product_step_id']);
            $table->renameColumn('product_step_id', 'plan_step_id');
        });

        Schema::table('manufacturing_executions', function (Blueprint $table) {
             $table->foreign('plan_step_id')->references('id')->on('plan_steps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->dropForeign(['plan_step_id']);
            $table->renameColumn('plan_step_id', 'product_step_id');
        });

        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->foreign('product_step_id')->references('id')->on('product_steps')->onDelete('cascade');
        });
    }
};
