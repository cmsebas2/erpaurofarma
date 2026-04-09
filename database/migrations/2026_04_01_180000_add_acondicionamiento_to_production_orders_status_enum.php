<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safe ALTER TABLE for ENUM using DB::statement as per Doctrine/DBAL best practices in older Laravel/MySQL
        DB::statement("ALTER TABLE production_orders MODIFY COLUMN status ENUM('PLANEADO','LIBERADO','EN_PROCESO','ACONDICIONAMIENTO','COMPLETADO','CUARENTENA','ANULADO') NOT NULL DEFAULT 'PLANEADO'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE production_orders MODIFY COLUMN status ENUM('PLANEADO','LIBERADO','EN_PROCESO','COMPLETADO','CUARENTENA','ANULADO') NOT NULL DEFAULT 'PLANEADO'");
    }
};
