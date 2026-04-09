<?php

namespace Database\Seeders;

use App\Models\FormulaIngredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QMutinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el Producto Principal
        $product = clone Product::updateOrCreate(
            ['name' => 'Q-MUTIN'],
            [
                'presentation' => '25 KG', // Resumen de presentaciones
                'vigencia_meses' => 24,
                'pharmaceutical_form' => 'POLVO ORAL',
                'image' => 'q-mutin.png',
                'base_batch_size' => 100,
                'base_unit' => 'KG',
                'status' => 'ACTIVO'
            ]
        );

        // Limpiar ingredientes anteriores si existieran
        $product->ingredients()->delete();

        // 2. Insertar Materias Primas / Granel
        $rawMaterials = [
            [
                'material_code' => '1692',
                'material_name' => 'TIAMULINA FUMARATO HIDROGENADO98%',
                'material_type' => 'MATERIA PRIMA',
                'function' => 'API',
                'unit' => 'KIL',
                'percentage' => 10.52,
                'quantity' => null, // Se puede calcular respecto al lote
            ],
            [
                'material_code' => '221012',
                'material_name' => 'BENZOATO DE SODIO',
                'material_type' => 'MATERIA PRIMA',
                'function' => 'PRESERVANTE - ANTIMICROBIANO',
                'unit' => 'KIL',
                'percentage' => 0.20,
                'quantity' => null,
            ],
            [
                'material_code' => '165',
                'material_name' => 'CASCARILLA DE ARROZ MOLIDO',
                'material_type' => 'MATERIA PRIMA',
                'function' => 'VEHICULO',
                'unit' => 'KIL',
                'percentage' => 88.78,
                'quantity' => null,
            ],
            [
                'material_code' => '2052',
                'material_name' => 'ACEITE MINERAL',
                'material_type' => 'MATERIA PRIMA',
                'function' => 'DISOLVENTE',
                'unit' => 'KIL',
                'percentage' => 0.50,
                'quantity' => null,
            ],
        ];

        foreach ($rawMaterials as $rm) {
            $product->ingredients()->create(array_merge($rm, ['presentation_id' => null]));
        }

        // 3. Crear su Presentación (Saco de 25 KG)
        $presentation = $product->presentations()->updateOrCreate(
            ['presentation_code' => 'A31002'],
            ['name' => 'Saco 25 KG']
        );

        // 4. Insertar Materiales de Empaque amarrados a la presentación
        $packagingMaterials = [
            [
                'material_code' => '311119',
                'material_name' => 'EMPAQUE IMPRESO BLANCO 55X81 CON LINNER',
                'material_type' => 'MATERIAL DE EMPAQUE',
                'function' => null,
                'unit' => 'UND',
                'percentage' => 100.00,
                'quantity' => 1,
            ],
            [
                'material_code' => '331030',
                'material_name' => 'ETIQUETA QMUTIN X 25 KG',
                'material_type' => 'MATERIAL DE ENVASE',
                'function' => null,
                'unit' => 'UND',
                'percentage' => 100.00,
                'quantity' => 1,
            ],
        ];

        foreach ($packagingMaterials as $pkg) {
            $product->ingredients()->create(array_merge($pkg, ['presentation_id' => $presentation->id]));
        }
    }
}
