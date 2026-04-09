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
        Schema::table('line_clearances', function (Blueprint $table) {
            $table->boolean('qa_presion_diferencial_conforme')->default(false)->after('verificado_por');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('line_clearances', function (Blueprint $table) {
            $table->dropColumn('qa_presion_diferencial_conforme');
        });
    }
};
