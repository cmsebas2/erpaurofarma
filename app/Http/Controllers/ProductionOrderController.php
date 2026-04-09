<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductionOrder;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{
    public function indexActive()
    {
        // Consultar todas las OPs ACTIVAS con sus relaciones para ver el progreso
        $ops = ProductionOrder::active()
            ->with(['product', 'opPresentations.presentation', 'lineClearances'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('batch.ops-activas', compact('ops'));
    }

    public function destroy(ProductionOrder $batch)
    {
        try {
            DB::beginTransaction();

            $op = $batch;
            
            // Las relaciones se eliminarán por cascada si está configurado en la DB, 
            // pero para estar seguros y manejar lógica de negocio:
            $op->opPresentations()->delete();
            $op->lineClearances()->delete();
            // También deberíamos limpiar otras tablas si no tienen cascada, aunque las migraciones dicen que sí.
            $op->delete();

            DB::commit();

            return redirect()->route('ops.activas')->with('success', "La Orden de Producción Lote {$op->lote} ha sido eliminada correctamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar la OP: ' . $e->getMessage()]);
        }
    }
}
