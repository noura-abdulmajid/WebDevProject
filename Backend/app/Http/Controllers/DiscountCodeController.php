<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use function Webmozart\Assert\Tests\StaticAnalysis\boolean;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class DiscountCodeController extends Controller
{

    public function checkDiscountCode(Request $request)
    {
        Log::info('[Discount-Code] Received discount code to check: ', $request->all());

        try {

            $attributes = $request->validate([
                'discount_code' => ['required', 'string'],
            ]);

            $discountCode = DiscountCode::where('discount_code', $attributes['discount_code'])->first();
            if (!$discountCode)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Discount Code not found.',
                ]);
            } else if (!$discountCode->active)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Discount Code is not valid.',
                    ]);
            } else if ($discountCode->active)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Discount Code is valid.',
                    'discount_percentage' => $discountCode->discount_percentage,
                ]);
            } else
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Unknown error occurred.',
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
