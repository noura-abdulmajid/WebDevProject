<?php

namespace App\Http\Controllers;

use App\Mail\MessageResponse;
use App\Mail\OrderShipped;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function returnAllOrders()
    {
        Log::info("[Order] Returning all orders");

        $orders = Order::select(
            'O_ID',
            'C_ID',
            'order_date',
            'shipping_address',
            'subtotal',
            'delivery_charge',
            'total_payment',
            'order_status',
        )->paginate(10);

        $order_items = OrderedItem::select(
            'OI_ID',
            'O_ID',
            'name',
            'price',
            'quantity'
        );

        return response()->json([
            'message' => 'Orders retrieved successfully.',
            'total_pages' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'orders' => $orders,
            'ordered_items' => $order_items
        ]);
    }

    public function setOrderStatus(Request $request)
    {
        Log::info("[Order] Setting order status");
        try {
            $attributes = $request->validate([
                'O_ID' => ['required', 'exists:orders,O_ID'],
                'order_status' => ['required', 'string', Rule::in(['Placed', 'Shipped', 'Delivered', 'Cancelled'])],
            ]);

            $order = Order::where('0_ID', $attributes['O_ID'])->first();

            if ($attributes['order_status'] === 'Shipped') {
                $order->order_status = 'Shipped';
                $order->save();
                $customer = Customer::where('C_ID', $order->C_ID)->first();
                // email shipping confirmation
                Mail::to($customer->email_address)->queue(new OrderShipped($order));

            } else if ($attributes['order_status'] === 'Delivered') {
                $order->order_status = 'Delivered';
                $order->save();
            } else if ($attributes['order_status'] === 'Placed') {
                $order->order_status = 'Placed';
                $order->save();
            } else if ($attributes['order_status'] === 'Cancelled') {
                $order->order_status = 'Cancelled';
                $order->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'order_status' => $order->order_status,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: Make sure order_status is "Placed", "Shipped", "Delivered" or "Cancelled"',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function returnShippingDetails(Request $request)
    {
        try {
            Log::info("[Order] Returning shipping details");
            $attributes = $request->validate([
                'O_ID' => ['required', 'exists:orders,O_ID'],
            ]);

            $order_details = Order::where('O_ID', $attributes['O_ID'])->first()->select(
                'shipping_address',
                'subtotal',
                'delivery_charge',
                'total_payment',);
            $customer_details = Customer::where('C_ID', $order_details->C_ID)->first()->select(
                'first_name',
                'last_name',
            );

            $shipping_details = $order_details->concat($customer_details);

            return response()->json([
                'success' => true,
                'shipping_details' => $shipping_details,
                'message' => 'Shipping details retrieved successfully.',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: Invalid Order ID',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }



    }
}
