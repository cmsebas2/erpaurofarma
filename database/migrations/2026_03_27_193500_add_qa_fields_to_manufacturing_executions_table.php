<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->foreignId('qa_user_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('qa_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacturing_executions', function (Blueprint $table) {
            $table->dropForeign(['qa_user_id']);
            $table->dropColumn(['qa_user_id', 'qa_verified_at']);
        });
    }
};
