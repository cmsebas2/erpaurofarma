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
            $table->string('master_code_header')->after('product_id')->nullable(); // A1PPR0010
            $table->string('internal_code')->after('master_code_header')->nullable(); // IF-304-1
            $table->string('ica_registry')->after('issue_date')->nullable();
            $table->text('requirements')->after('objective')->nullable();
            $table->text('observations')->after('potency_adjustment_logic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_manufacturing_plans', function (Blueprint $table) {
            $table->dropColumn(['master_code_header', 'internal_code', 'ica_registry', 'requirements', 'observations']);
        });
    }
};
