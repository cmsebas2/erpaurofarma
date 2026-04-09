<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupProductionOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-ops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia la tabla de Órdenes de Producción eliminando registros huérfanos o anulados.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpieza de Órdenes de Producción...');

        // 1. Eliminar OPs en estado ANULADO
        $anuladas = \App\Models\ProductionOrder::where('status', 'ANULADO')->get();
        $countAnuladas = $anuladas->count();
        foreach ($anuladas as $op) {
            $op->delete(); // El cascade de la DB se encarga de lo demás
        }
        $this->info("Se eliminaron {$countAnuladas} órdenes anuladas.");

        // 2. Eliminar OPs sin presentaciones (huérfanas/incompletas)
        $huerfanas = \App\Models\ProductionOrder::doesntHave('opPresentations')->get();
        $countHuerfanas = $huerfanas->count();
        foreach ($huerfanas as $op) {
            $op->delete();
        }
        $this->info("Se eliminaron {$countHuerfanas} órdenes sin presentaciones (incompletas).");

        // 3. Eliminar OPs sin producto asociado (si existieran por fallos de FK)
        $sinProducto = \App\Models\ProductionOrder::doesntHave('product')->get();
        $countSinProducto = $sinProducto->count();
        foreach ($sinProducto as $op) {
            $op->delete();
        }
        
        $this->info("Se eliminaron {$countSinProducto} órdenes sin producto asociado.");
        $this->info('Proceso de limpieza completado.');
    }
}
