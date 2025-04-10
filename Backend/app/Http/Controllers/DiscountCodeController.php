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
    public function returnAllDiscountCodes()
    {
        $allDiscountCodes = DiscountCode::all();

        return response()->json([
            'success' => true,
            'message' => 'all discount codes found successfully',
            'discount_codes' => $allDiscountCodes
        ]);
    }

    public function createNewDiscountCode(Request $request)
    {
        try {
            $attributes = $request->validate([
                'discount_name' => ['required', 'string'],
                'discount_code' => ['required', 'string', 'unique:discount_codes', 'max:255'],
                'discount_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
                'active' => ['required', 'boolean'],
            ]);

            $discountCode = DiscountCode::create([$attributes]);

            return response()->json([
                'success' => true,
                'discount_code' => $discountCode->discount_code,
                'discount_percentage' => $discountCode->discount_percentage,

            ]);
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

    public function destroy(Request $request): JsonResponse
    {
        try {
            $attributes = $request->validate([
                'D_ID' => ['required', 'numeric', 'exists:discount_codes,D_ID'],
            ]);

            DiscountCode::destroy($attributes['D_ID']);

            return response()->json([
                'success' => true,
                'discount_code' => $attributes['D_ID'],
                'message' => 'Discount Code Deleted',
            ]);
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

    public function editDiscountCode(Request $request)
    {
        Log::info('[DiscountCode] Editing discount code');
        try {
            $attributes = $request->validate([
                'D_ID' => ['required', 'numeric', 'exists:discount_codes,D_ID'],
                'discount_name' => ['string'],
                'discount_code' => ['string'],
                'discount_percentage' => ['integer', 'min:0', 'max:100'],
                'active' => ['boolean'],

            ]);

            $discountCode = DiscountCode::find($attributes['D_ID'])->update($attributes);

            return response()->json([
                'success' => true,
                'message' => 'Discount Code Updated Successfully',
                'discount_code' => $discountCode,
            ]);


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
