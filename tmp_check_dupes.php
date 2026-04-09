<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\ManufacturingExecution;

$dupes = ManufacturingExecution::select('production_order_id', 'plan_step_id', 'plan_step_ingredient_id', DB::raw('COUNT(*) as total'))
    ->groupBy('production_order_id', 'plan_step_id', 'plan_step_ingredient_id')
    ->having('total', '>', 1)
    ->get();

if ($dupes->count() > 0) {
    echo "Found " . $dupes->count() . " duplicate sets.\n";
    foreach ($dupes as $d) {
        echo "Order: {$d->production_order_id}, Step: {$d->plan_step_id}, Ing: " . ($d->plan_step_ingredient_id ?? 'NULL') . ", Total: {$d->total}\n";
    }
} else {
    echo "No duplicates found.\n";
}
