<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        Log::info('Checkout method triggered');
        Log::info('Incoming JSON', ['request_data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'customer.first_name' => 'required|string|max:255',
            'customer.surname' => 'required|string|max:255',
            'customer.email' => 'required|email',
            'order.items' => 'required|array|min:1',
            'order.items.*.name' => 'required|string|max:255',
            'order.items.*.quantity' => 'required|integer|min:1',
            'order.items.*.price' => 'required|numeric|min:0',
            'order.totalQuantity' => 'required|integer|min:1',
            'order.totalPrice' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Validation passed');
            DB::beginTransaction();

            if (Auth::check()) {
                $userId = Auth::id();
            } else {

                $customer = Customer::where('email_address', $request->input('customer.email'))->first();

                if (!$customer) {
                    $customer = Customer::create([
                        'first_name' => $request->input('customer.first_name'),
                        'surname' => $request->input('customer.surname'),
                        'email_address' => $request->input('customer.email'),
                        'password' => bcrypt('guest1234'),
                        'tel_no' => $request->input('customer.phoneNumber', null),
                        'shipping_address' => $request->input('customer.shippingAddress', 'Default Shipping Address'),
                        'billing_address' => $request->input('customer.billingAddress', 'Default Billing Address'),
                        'date_joined' => now(),
                    ]);
                }

                $userId = $customer->C_ID;
            }

            $order = Order::create([
                'C_ID' => $userId,
                'order_date' => now(),
                'shipping_address' => $request->input('customer.shippingAddress', "Default Shipping Address"),
                'subtotal' => $request->input('order.totalPrice'),
                'delivery_charge' => 10.00,
                'total_payment' => $request->input('order.totalPrice') + 10.00,
                'order_status' => 'Placed',
            ]);

            Log::info('Order created successfully', ['order_id' => $order->id]);

            foreach ($request->input('order.items') as $itemData) {
                OrderedItem::create([
                    'order_id' => $order->id,
                    'name' => $itemData['name'],
                    'price' => $itemData['price'],
                    'quantity' => $itemData['quantity']
                ]);

                Log::info('Item added to order', ['item_data' => $itemData]);
            }

            DB::commit();
            Log::info('Transaction committed successfully');

            return response()->json([
                'message' => 'Order placed successfully.',
                'order_id' => $order->id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction rolled back', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}