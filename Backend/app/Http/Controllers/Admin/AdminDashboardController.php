<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboardStats()
    {
        Log::info('Dashboard statistics ......');
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $stats = [
            'total_users' => Customer::count(),
            'total_orders' => Order::count(),
            'total_sales' => Order::sum('total_payment'),
        ];
        // Yearly sales and buyers statistics
        $yearly_sales = Order::selectRaw('YEAR(order_date) as year, SUM(total_payment) as total_sales, COUNT(DISTINCT C_ID) as total_buyers')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->year => [
                        'total_sales' => $item->total_sales,
                        'total_buyers' => $item->total_buyers,
                    ],
                ];
            });

        // Monthly sales and buyers statistics grouped by year
        $monthly_sales = Order::selectRaw('YEAR(order_date) as year, MONTH(order_date) as month, SUM(total_payment) as total_sales, COUNT(DISTINCT C_ID) as total_buyers')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get()
            ->groupBy('year')
            ->map(function ($yearlyData) {
                return $yearlyData->mapWithKeys(function ($item) {
                    return [
                        $item->month => [
                            'total_sales' => $item->total_sales,
                            'total_buyers' => $item->total_buyers,
                        ],
                    ];
                });
            });

        $stats['yearly_sales'] = $yearly_sales;
        $stats['monthly_sales'] = $monthly_sales;


        Log::info('Dashboard statistics retrieved successfully by admin: ' . $admin->email);

        return response()->json([
            'message' => 'Dashboard statistics retrieved successfully.',
            'stats' => $stats,
        ], 200);
    }
}
