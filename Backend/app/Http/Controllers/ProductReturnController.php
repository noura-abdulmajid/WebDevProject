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

    public function getAllReturnRequests()
    {
        Log::info("Returning all return requests");
        //$returnRequests = ProductReturn::all();
        $returnRequests = ProductReturn::select(
            'R_ID',
            'C_ID',
            'return_reason',
            'return_value',
            'date_started',
            'return_deadline',
            'receipt_status',
            'receipt_date',
            'refund_status',
            'refund_date')->paginate(10);

        return response()->json([
            'message' => 'All return requests retrieved successfully',
            'total_pages' => $returnRequests->total(),
            'current_page' => $returnRequests->currentPage(),
            'last_page' => $returnRequests->lastPage(),
            'return_requests' => $returnRequests,
        ]);
    }

    public function setReceived(Request $request)
    {
        try {
            $attributes = $request->validate([
                'R_ID' => ['required', 'integer'],
            ]);

            $return = ProductReturn::where('R_ID', $request->R_ID)->first();

            if ($return->receipt_status === true) {
                return response()->json([
                    'success' => true,
                    'message' => 'return already marked received',
                ]);
            } else {
                $return->receipt_status = true;
                $return->save();
                return response()->json([
                    'success' => true,
                    'message' => 'return marked received',
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

    public function confirmOrRejectReturn(Request $request)
    {
        try {
            $attributes = $request->validate([
                'R_ID' => ['required', 'integer'],
                'refund_response' => ['required', 'string', Rule::in(['Confirm', 'Reject'])],
                'response_reason' => ['required', 'string'],
            ]);

            $return = ProductReturn::where('R_ID', $request->R_ID)->first();

            if ($attributes['refund_response'] === 'Confirm') {
                return response()->json([
                    'success' => true,
                    'message' => 'return confirmed successfully',
                ]);
            } else {
                $return->refund_rejected = true;
                $return->rejection_reason = $attributes['refund_response'];
                $return->save();
                // email customer w/ rejection reason
                Mail::to($return->email)->queue(new ReturnRejection($return));

                return response()->json([
                    'success' => true,
                    'message' => 'return rejected successfully',
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: Must be "Accept" or "Reject"',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }

    }

    public function setRefunded(Request $request)
    {
        try {
            $attributes = $request->validate([
                'R_ID' => ['required', 'integer'],
//                'refund_status' => ['required', 'string', Rule::in(['Confirmed'])],
            ]);

            $refund = ProductReturn::where('R_ID', $request->R_ID)->first();

            if ($refund->refund_status === true) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Already Refunded',
                ]);
            } else {
                if ($refund->refund_status === false) {
                    $refund->refund_status = true;
                    $refund->save();
                    Mail::to($refund->email)->queue(new RefundConfirmation($refund));

                    return response()->json([
                        'success' => true,
                        'message' => 'Order Refunded',
                        // email customer
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error Occurred - Order Refund failed',
                    ]);
                }
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
