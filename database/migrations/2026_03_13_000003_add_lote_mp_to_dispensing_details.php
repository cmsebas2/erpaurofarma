<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispensing_details', function (Blueprint $table) {
            $table->string('lote_mp', 100)->nullable()->after('formula_ingredient_id');
        });
    }

    public function down(): void
    {
        Schema::table('dispensing_details', function (Blueprint $table) {
            $table->dropColumn('lote_mp');
        });
    }
};
