<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

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
}
