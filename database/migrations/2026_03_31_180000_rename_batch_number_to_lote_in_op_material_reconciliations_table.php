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
        Schema::table('op_material_reconciliations', function (Blueprint $table) {
            $table->renameColumn('batch_number', 'lote');
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('op_material_reconciliations', function (Blueprint $table) {
            $table->renameColumn('lote', 'batch_number');
        });
    }
};
