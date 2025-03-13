<?php

namespace App\Http\Controllers\Admin;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AdminProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $products = Products::select('P_ID', 'p_name', 'description', 'categories', 'colours', 'photo', 'price', 'overall_stock_status')
            ->paginate(10);

        Log::info('All products retrieved successfully by admin: ' . $admin->email);

        return response()->json([
            'message' => 'All products retrieved successfully.',
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
        ], 200);
    }
}
