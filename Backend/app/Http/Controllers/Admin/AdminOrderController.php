<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display the specified order with detailed information,
     * including the associated customer and ordered items.
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'orderedItems'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update the order with only the relevant fields
        $order->update($request->only([
            'order_date',
            'shipping_address',
            'subtotal',
            'delivery_charge',
            'total_payment'
        ]));

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}