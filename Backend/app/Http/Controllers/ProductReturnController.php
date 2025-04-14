<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\RefundConfirmation;
use App\Mail\ReturnRejection;
use App\Models\Order;
use App\Models\ProductReturn;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Stripe\Refund;

class ProductReturnController extends Controller
{
    public function newReturn(Request $request)
    {
        Log::info('[Return] Received request data: ', $request->all());

        try {

            $attributes = $request->validate([
                'O_ID' => ['required', 'numeric'],
                'email' => ['required', 'email'],
                'return_reason' => ['required', 'string'],
            ]);

            //get order
            $order = Order::where('O_ID', $request->O_ID)->first();
            $deadline = $order->order_date->modify('+30 day');
            $current_date = new DateTime();

            if ($deadline < $current_date) {
                return response()->json([
                    'success' => true,
                    'message' => 'Return rejected: over 30 days have passed since your purchase'
                ]);
            } else {
                $return = new ProductReturn();
                $return->O_ID = $attributes['order_id'];
                $return->C_ID = $order->C_ID;
                $return->return_reason = $attributes['return_reason'];
                $return->return_value = $order->total_payment;
                $return->date_started = date('Y-m-d');
                $return->return_deadline = $deadline;
                $return->receipt_status = false;
                $return->refund_status = false;
                $return->save();
                return response()->json([
                    'success' => true,
                    'message' => 'return request received and recorded',
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
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
