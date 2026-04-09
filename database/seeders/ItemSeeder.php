<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\File;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/maestro_items.csv');

        if (!File::exists($csvPath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$csvPath}");
            return;
        }

        // Leer el archivo considerando que puede venir con otra codificación desde Windows/Excel
        $file = fopen($csvPath, 'r');
        
        // Leer la primera línea (cabeceras)
        $headers = fgetcsv($file, 0, ',');
        
        if (!$headers) {
            $this->command->error("No se pudieron leer las cabeceras del CSV.");
            return;
        }

        // Limpiar cabeceras de caracteres raros (BOM)
        $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);

        $count = 0;
        
        $this->command->info('Importando ítems maestros desde CSV...');
        
        while (($row = fgetcsv($file, 0, ',')) !== false) {
            // Saltarse filas vacías
            if (empty(array_filter($row))) {
                continue;
            }

            // Mapear los datos según el nuevo formato de 2 columnas:
            // 0: ITEM, 1: NOMBRE
            $itemCode = trim($row[0] ?? '');
            
            if (empty($itemCode)) {
                continue;
            }

            Item::updateOrCreate(
                ['item_code' => $itemCode],
                [
                    'description' => trim(mb_convert_encoding($row[1] ?? '', 'UTF-8', 'ISO-8859-1')),
                    
                    // Valores por defecto ya que las columnas fueron eliminadas del CSV
                    'reference' => null,
                    'ext_1_detail' => null,
                    'ext_2_detail' => null,
                    'inventory_type' => null,
                    'item_type' => null,
                    'tax_group' => null,
                    'discount_group' => null,
                    'inventory_uom' => 'UND', // Unidad base por defecto
                    'order_uom' => null,
                    'packaging_uom' => null,
                    
                    // Booleanos
                    'is_purchased' => false,
                    'is_sold' => false,
                    'is_manufactured' => false,
                    'has_extension' => false,
                    'manages_batches' => false,
                    'batch_assignment' => false,
                    'manages_serial' => false,
                ]
            );
            
            $count++;
            
            if ($count % 500 === 0) {
                $this->command->info("Procesados {$count} ítems...");
            }
        }

        fclose($file);
        
        $this->command->info("¡Importación completada! Se insertaron/actualizaron {$count} ítems en total.");
    }
}
