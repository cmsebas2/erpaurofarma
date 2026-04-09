<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Catálogo de Productos (Frontend Interactivo)
    Route::get('/productos', [\App\Http\Controllers\ProductController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [\App\Http\Controllers\ProductController::class, 'create'])->name('productos.create');
    Route::post('/productos/crear', [\App\Http\Controllers\ProductController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('productos.show');
    Route::get('/productos/{id}/imprimir', [\App\Http\Controllers\ProductController::class, 'imprimirFicha'])->name('productos.imprimir');
    Route::get('/productos/{id}/editar', [\App\Http\Controllers\ProductController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('productos.destroy');
    
    // EBR Master Builder (Configurador de Instructivos)
    Route::get('/productos/{id}/instructivo', [\App\Http\Controllers\ProductController::class, 'editInstructivo'])->name('productos.instructivo.edit');
    Route::post('/productos/{id}/instructivo', [\App\Http\Controllers\ProductController::class, 'updateInstructivo'])->name('productos.instructivo.update');
    Route::delete('/instructivo/{id}', [\App\Http\Controllers\ProductController::class, 'deleteInstructivo'])->name('productos.instructivo.destroy');




    // API autocompletado de ítems
    Route::get('/api/items/{codigo}', [\App\Http\Controllers\ProductController::class, 'apiGetItem']);

    // Electronic Batch Record (EBR)
    Route::get('/batch/iniciar', [\App\Http\Controllers\BatchController::class, 'iniciar'])->name('batch.iniciar');
    Route::post('/batch/iniciar', [\App\Http\Controllers\BatchController::class, 'store'])->name('batch.store');
    
    // Conciliación de Materiales
    Route::get('/batch/{batch}/conciliacion', [\App\Http\Controllers\BatchController::class, 'createReconciliation'])->name('batch.conciliacion');
    Route::post('/batch/{batch}/conciliacion', [\App\Http\Controllers\BatchController::class, 'storeReconciliation'])->name('batch.conciliacion.store');
    Route::post('/batch/{batch}/conciliacion/sign', [\App\Http\Controllers\BatchController::class, 'signReconciliation'])->name('batch.conciliacion.sign');
    
    // Despeje de Línea
    Route::get('/batch/{batch}/despeje-linea', [\App\Http\Controllers\BatchController::class, 'createLineClearance'])->name('batch.despeje');
    Route::post('/batch/{batch}/despeje-linea', [\App\Http\Controllers\BatchController::class, 'storeLineClearance'])->name('batch.despeje.store');

    // Doble Verificación QA
    Route::post('/batch/{batch}/qa-credentials', [\App\Http\Controllers\BatchController::class, 'validateQaCredentials'])->name('batch.qa.credentials');
    Route::post('/batch/{batch}/qa-verification', [\App\Http\Controllers\BatchController::class, 'storeQaVerification'])->name('batch.qa.verification');

    // Módulo 3: Fabricación (Manufacturing)
    Route::get('/batch/{batch}/fabricacion', [\App\Http\Controllers\BatchController::class, 'createManufacturing'])->name('batch.fabricacion');
    Route::post('/batch/{batch}/fabricacion/step', [\App\Http\Controllers\BatchController::class, 'storeManufacturingStep'])->name('batch.fabricacion.store');
    Route::post('/batch/{batch}/fabricacion/cerrar', [\App\Http\Controllers\BatchController::class, 'finishManufacturing'])->name('batch.fabricacion.cerrar');
    Route::post('/batch/{batch}/fabricacion/dynamic-step', [\App\Http\Controllers\BatchController::class, 'storeManufacturingStepDynamic'])->name('batch.fabricacion.store.dynamic');
    Route::post('/batch/{batch}/fabricacion/verify-step', [\App\Http\Controllers\BatchController::class, 'verifyManufacturingStepDynamic'])->name('batch.fabricacion.verify.dynamic');

    // Dispensación
    Route::get('/batch/{batch}/dispensacion', [\App\Http\Controllers\BatchController::class, 'createDispensing'])->name('batch.dispensacion');
    Route::post('/batch/{batch}/dispensacion/detalle', [\App\Http\Controllers\BatchController::class, 'storeDispensingDetail'])->name('batch.dispensacion.detalle');
    Route::post('/batch/{batch}/dispensacion/cerrar', [\App\Http\Controllers\BatchController::class, 'closeDispensing'])->name('batch.dispensacion.cerrar');

    // Módulo 6: Envase (Format A3PPR0010)
    Route::get('/batch/{batch}/envase', [\App\Http\Controllers\BatchController::class, 'createPackaging'])->name('batch.envase');
    Route::post('/batch/{batch}/envase/store', [\App\Http\Controllers\BatchController::class, 'storePackaging'])->name('batch.envase.store');
    Route::post('/batch/{batch}/envase/weight', [\App\Http\Controllers\BatchController::class, 'storePackagingWeight'])->name('batch.envase.weight.store');
    Route::post('/batch/{batch}/envase/verify', [\App\Http\Controllers\BatchController::class, 'verifyPackaging'])->name('batch.envase.verify');

    // Gestión de OPs (Supervisor)
    Route::get('/ops/activas', [\App\Http\Controllers\ProductionOrderController::class, 'indexActive'])->name('ops.activas');
    Route::delete('/ops/{batch}', [\App\Http\Controllers\ProductionOrderController::class, 'destroy'])->name('ops.destroy');
});
