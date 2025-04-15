<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    public function index()
    {
        try {
            $refunds = DB::table('refunds')
                ->join('orders', 'refunds.order_id', '=', 'orders.O_ID')
                ->where('orders.C_ID', Auth::id())
                ->select('refunds.*', 'orders.O_ID as order_number')
                ->get();

            return response()->json($refunds);
        } catch (\Exception $e) {
            Log::error('Error fetching refunds: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch refunds'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,O_ID',
                'reason' => 'required|string'
            ]);

            // Check if user owns the order
            $order = Order::where('O_ID', $request->order_id)
                ->where('C_ID', Auth::id())
                ->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found or unauthorized'], 404);
            }

            // Check if refund already exists
            $existingRefund = Refund::where('order_id', $request->order_id)->first();
            if ($existingRefund) {
                return response()->json(['message' => 'Refund request already exists for this order'], 422);
            }

            // Create refund record
            $refund = Refund::create([
                'order_id' => $request->order_id,
                'amount' => $order->total_payment,
                'reason' => $request->reason,
                'status' => 'pending'
            ]);

            return response()->json([
                'message' => 'Refund request submitted successfully',
                'refund' => $refund
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating refund: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to submit refund request'], 500);
        }
    }

    public function show($id)
    {
        try {
            $refund = DB::table('refunds')
                ->join('orders', 'refunds.order_id', '=', 'orders.O_ID')
                ->where('refunds.id', $id)
                ->where('orders.C_ID', Auth::id())
                ->select('refunds.*', 'orders.O_ID as order_number')
                ->first();

            if (!$refund) {
                return response()->json(['message' => 'Refund not found or unauthorized'], 404);
            }

            return response()->json($refund);
        } catch (\Exception $e) {
            Log::error('Error fetching refund: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch refund details'], 500);
        }
    }
} 