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
        Schema::table('manufacturing_executions', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('ipc_result')->nullable()->after('observations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manufacturing_executions', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('ipc_result');
        });
    }
};
