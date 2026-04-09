<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->decimal('elapsed_minutes', 8, 2)->nullable()->after('rpm');
        });
    }

    public function down(): void
    {
        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->dropColumn('elapsed_minutes');
        });
    }
};
