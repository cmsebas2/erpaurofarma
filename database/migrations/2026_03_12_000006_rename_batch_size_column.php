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
        Schema::table('product_manufacturing_plans', function (Blueprint $table) {
            $table->renameColumn('base_batch_size', 'master_batch_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_manufacturing_plans', function (Blueprint $table) {
            $table->renameColumn('master_batch_size', 'base_batch_size');
        });
    }
};
