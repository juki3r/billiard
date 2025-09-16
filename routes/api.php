<?php

use App\Models\Sale;
use App\Models\TableStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Esp32Controller;
use App\Http\Controllers\TableTimeController;
use Illuminate\Support\Facades\DB;

// Route::post('/sales', function (Request $request) {
//     $validated = $request->validate([
//         'table_id' => 'required|string',
//         'coins' => 'required|integer|min:1',
//         'time_sec' => 'required|integer|min:1',
//     ]);

//     // Record sale
//     $sale = Sale::create($validated);

//     // Update table status to match time
//     $tableStatus = TableStatus::firstOrCreate(
//         ['table_id' => $validated['table_id']],
//         ['time_remaining_sec' => 0]
//     );

//     $tableStatus->time_remaining_sec = max($tableStatus->time_remaining_sec, $validated['time_sec']);
//     $tableStatus->save();

//     return response()->json([
//         'success' => true,
//         'message' => 'Sale recorded',
//         'sale_id' => $sale->id,
//         'current_table_time' => $tableStatus->time_remaining_sec
//     ]);
// });

Route::get('/ping_test', function () {
    return response()->json([
        "message" => "hello"
    ]);
});

Route::post('/sales', [Esp32Controller::class, 'recordSale']);

Route::get('/override', [Esp32Controller::class, 'check']); // for ESP32 fetch
Route::post('/override', [Esp32Controller::class, 'set']);   // optional: admin override

Route::post('/time', [TableTimeController::class, 'update']);
Route::get('/time/{table_id}', [TableTimeController::class, 'show']);

Route::get('/sales/total', function () {
    $summary = DB::table('sales')
        ->select('table_id', DB::raw('SUM(coins) as total_sales'))
        ->groupBy('table_id')
        ->get();

    $grandTotal = $summary->sum('total_sales');

    return response()->json([
        'tables' => $summary,
        'grand_total' => $grandTotal,
    ]);
});

Route::post('/sales/reset', function () {
    DB::table('sales')->truncate(); // or reset totals
    return response()->json(['message' => 'Sales reset successfully']);
});
