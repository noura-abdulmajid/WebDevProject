<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    /**
     * Display the specified order with detailed information,
     * including the associated customer and ordered items.
     */
    public function show($id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        info('Retrieving order with id: ' . $id);
        $order = Order::with(['customer', 'orderedItems'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        Log::info('Admin retrieved order successfully: Admin ID - ' . $admin->id . ', Order ID - ' . $id);

        return response()->json($order);
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, $id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        info('Updating order with id: ' . $id);
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

        Log::info('Admin updated order successfully: Admin ID - ' . $admin->id . ', Order ID - ' . $id);

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        info('Deleting order with id: ' . $id);
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        Log::info('Admin deleted order successfully: Admin ID - ' . $admin->id . ', Order ID - ' . $id);

        return response()->json(['message' => 'Order deleted successfully']);
    }
}