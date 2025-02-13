<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageVisit;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class LogVisitController extends Controller
{
    public function visit(Request $request)
    {
        Log::info('Page Visit Function ......');
        $ip = $request->ip();
        $page = $request->get('page');
        $timestamp = $request->get('timestamp');
        $formattedTimestamp = Carbon::parse($timestamp)->format('Y-m-d H:i:s');

        PageVisit::create([
            'ip' => $ip,
            'page' => $page,
            'timestamp' => $formattedTimestamp,
        ]);


        Log::info('Page visit logged', [
            'ip' => $ip,
            'page' => $page,
            'timestamp' => $timestamp,
        ]);

        return response()->json([
            'message' => 'Visit logged successfully.',
        ]);
    }
}