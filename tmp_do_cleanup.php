<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\ManufacturingExecution;

DB::beginTransaction();
try {
    $dupes = ManufacturingExecution::select('production_order_id', 'plan_step_id', 'plan_step_ingredient_id', DB::raw('GROUP_CONCAT(id) as ids'))
        ->groupBy('production_order_id', 'plan_step_id', 'plan_step_ingredient_id')
        ->having(DB::raw('COUNT(*)'), '>', 1)
        ->get();

    $deletedCount = 0;
    foreach ($dupes as $d) {
        $ids = explode(',', $d->ids);
        sort($ids);
        $keep = array_pop($ids); // Keep the latest ID
        $deleted = ManufacturingExecution::whereIn('id', $ids)->delete();
        $deletedCount += $deleted;
    }

    DB::commit();
    echo "Cleanup complete. Deleted $deletedCount records.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
