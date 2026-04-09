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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique(); // Item
            $table->string('reference')->nullable(); // Referencia
            $table->string('description'); // Desc. item
            $table->text('ext_1_detail')->nullable(); // Ext. 1 detalle
            $table->text('ext_2_detail')->nullable(); // Ext. 2 detalle
            $table->string('inventory_type')->nullable(); // Tipo inventario
            $table->string('item_type')->nullable(); // Tipo item
            $table->string('tax_group')->nullable(); // Grupo impositivo
            $table->string('discount_group')->nullable(); // Grupo dscto.
            $table->string('inventory_uom', 10)->nullable(); // U.M. invent.
            $table->string('order_uom', 10)->nullable(); // U.M. orden
            $table->string('packaging_uom', 10)->nullable(); // U.M. empaque
            $table->boolean('is_purchased')->default(false); // Compra
            $table->boolean('is_sold')->default(false); // Venta
            $table->boolean('is_manufactured')->default(false); // Manufactura
            $table->boolean('has_extension')->default(false); // Extensión
            $table->boolean('manages_batches')->default(false); // Maneja lote
            $table->boolean('batch_assignment')->default(false); // Asignación lote
            $table->boolean('manages_serial')->default(false); // Maneja serial
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
