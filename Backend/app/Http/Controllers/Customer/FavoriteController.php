<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function index()
    {
        try {
            Log::info('Attempting to get favorites...');
            
            $user = $this->validateCustomerToken();
            if ($user instanceof JsonResponse) {
                return $user;
            }

            $C_ID = $user->C_ID;

            // Get favorites with product details
            $favorites = DB::table('favorites')
                ->join('products', 'favorites.P_ID', '=', 'products.P_ID')
                ->where('favorites.C_ID', $C_ID)
                ->select('products.*')
                ->get();

            Log::info('Favorites retrieved successfully for customer: ' . $C_ID);

            return response()->json([
                'message' => 'Favorites retrieved successfully.',
                'favorites' => $favorites
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error retrieving favorites: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred retrieving favorites.'], 500);
        }
    }

    public function store(Request $request, $product)
    {
        try {
            $user = $this->validateCustomerToken();
            if ($user instanceof JsonResponse) {
                return $user;
            }

            $C_ID = $user->C_ID;

            // Check if favorite already exists
            $exists = DB::table('favorites')
                ->where('C_ID', $C_ID)
                ->where('P_ID', $product)
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'Product already in favorites.'], 200);
            }

            // Add to favorites
            DB::table('favorites')->insert([
                'C_ID' => $C_ID,
                'P_ID' => $product,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['message' => 'Product added to favorites successfully.'], 201);

        } catch (\Exception $e) {
            Log::error('Error adding to favorites: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred adding to favorites.'], 500);
        }
    }

    public function destroy($product)
    {
        try {
            $user = $this->validateCustomerToken();
            if ($user instanceof JsonResponse) {
                return $user;
            }

            $C_ID = $user->C_ID;

            // Remove from favorites
            DB::table('favorites')
                ->where('C_ID', $C_ID)
                ->where('P_ID', $product)
                ->delete();

            return response()->json(['message' => 'Product removed from favorites successfully.'], 200);

        } catch (\Exception $e) {
            Log::error('Error removing from favorites: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred removing from favorites.'], 500);
        }
    }
} 