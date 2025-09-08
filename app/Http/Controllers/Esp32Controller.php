<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\OverrideTime;
use Illuminate\Http\Request;

class Esp32Controller extends Controller
{
    // ✅ Endpoint to receive sales
    public function recordSale(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|string',
            'coins' => 'required|integer|min:1',
            'time_sec' => 'required|integer|min:0',
        ]);

        $sale = Sale::create($validated);

        return response()->json([
            'success' => true,
            'sale_id' => $sale->id
        ]);
    }

    // ✅ Health ping from ESP32
    public function ping(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|string',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'pong',
            // optional override time, e.g., 60 seconds
            'override_time_sec' => 0
        ]);
    }


    public function check(Request $request)
    {
        $tableId = $request->query('table_id', 'TABLE-1'); // default
        $override = OverrideTime::where('table_id', $tableId)->first();

        if (!$override) {
            return response()->json(null); // null → no override
        }

        return response()->json($override->override_time); // can be -1, >=0, or null
    }

    // optional: admin can set override
    public function set(Request $request)
    {
        $request->validate([
            'table_id' => 'required|string',
            'override_time' => 'nullable|integer',
        ]);

        $override = OverrideTime::updateOrCreate(
            ['table_id' => $request->table_id],
            ['override_time' => $request->override_time]
        );

        return response()->json($override);
    }
}
