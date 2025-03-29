<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,O_ID',
            'reason' => 'required|string|max:255',
        ]);

        $order = Order::where('O_ID', $request->order_id)->firstOrFail();

        // Check if order belongs to current user
        if ($order->C_ID !== Auth::id()) {
            return response()->json(['message' => 'You do not have permission to request a refund for this order'], 403);
        }

        // Check if order status allows refund
        if (!in_array($order->status, ['completed', 'processing'])) {
            return response()->json(['message' => 'This order status does not allow refunds'], 400);
        }

        $refund = Refund::create([
            'order_id' => $order->O_ID,
            'amount' => $order->total_payment,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Refund request submitted successfully',
            'refund' => $refund
        ], 201);
    }

    public function index()
    {
        $refunds = Refund::with('order')
            ->whereHas('order', function ($query) {
                $query->where('C_ID', Auth::id());
            })
            ->latest()
            ->get();

        return response()->json($refunds);
    }

    public function show(Refund $refund)
    {
        if ($refund->order->C_ID !== Auth::id()) {
            return response()->json(['message' => 'You do not have permission to view this refund'], 403);
        }

        return response()->json($refund->load('order'));
    }
} 