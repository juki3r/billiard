<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table; // Assuming you have a Table model
use Illuminate\Support\Facades\Log;

class TableTimeController extends Controller
{
    /**
     * Update timeRemaining from ESP32
     */
    public function update(Request $request)
    {
        $request->validate([
            'table_id' => 'required|string',
            'time_remaining' => 'required|integer|min:0',
        ]);

        $tableId = $request->input('table_id');
        $timeRemaining = $request->input('time_remaining'); // in seconds

        // Find table by table_id or create new
        $table = \App\Models\Table::firstOrCreate(
            ['table_id' => $tableId],
            ['time_remaining' => $timeRemaining]
        );

        // Update time_remaining
        $table->time_remaining = $timeRemaining;
        $table->save();

        Log::info("Table {$tableId} updated: time_remaining={$timeRemaining}");

        return response()->json([
            'status' => 'success',
            'table_id' => $tableId,
            'time_remaining' => $timeRemaining
        ]);
    }

    public function show($table_id)
    {
        $table = Table::where('table_id', $table_id)->first();

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        return response()->json($table);
    }
}
