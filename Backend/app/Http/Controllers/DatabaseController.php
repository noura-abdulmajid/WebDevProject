<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseController extends Controller
{
    public function testConnection()
    {
        try {

            DB::connection()->getPdo();

            Log::info('Test_Connection Database connection successful！');
            return response()->json(['message' => 'Test_Connection Database connection successful！'], 200);
        } catch (\Exception $e) {

            Log::error('Test_Connection Database connection failed：' . $e->getMessage());
            return response()->json(['message' => 'Database connection failed！', 'error' => $e->getMessage()], 500);
        }
    }
}
