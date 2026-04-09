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
        Schema::table('plan_steps', function (Blueprint $table) {
            // Adding IPC fields
            $table->string('ipc_test_type')->after('mesh_size')->nullable();
            $table->string('ipc_specification')->after('ipc_test_type')->nullable();
            
            // The type column is an enum in the original migration. 
            // We'll change it to string to avoid complex enum modifications and allow the new 'CONTROL_PROCESO'.
            $table->string('type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_steps', function (Blueprint $table) {
            $table->dropColumn(['ipc_test_type', 'ipc_specification']);
            // We won't revert the change to string as it's more flexible.
        });
    }
};
