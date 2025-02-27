<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Products;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AdminUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function getAllUsers(Request $request)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $search = $request->input('search');

        $query = Customer::select('C_ID', 'first_name', 'surname', 'email_address', 'tel_no', 'shipping_address', 'billing_address');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%")
                    ->orWhere('tel_no', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        foreach ($users as &$user) {
            $customerId = $user->C_ID;
            $orders = DB::table('orders')
                ->leftJoin('shipped', 'orders.O_ID', '=', 'shipped.O_ID')
                ->selectRaw("
                SUM(CASE WHEN shipped.delivery_status IN ('pending', 'shipped') THEN 1 ELSE 0 END) as ongoing_orders,
                SUM(CASE WHEN shipped.delivery_status = 'delivered' THEN 1 ELSE 0 END) as completed_orders,
                SUM(orders.total_payment) as total_spent
            ")
                ->where('orders.C_ID', $customerId)
                ->first();

            $user->orders_summary = [
                'ongoing' => $orders->ongoing_orders ?? 0,
                'completed' => $orders->completed_orders ?? 0,
                'total_spent' => $orders->total_spent ?? 0.0,
            ];

            // Debug
//            Log::info('Order statistics for user', [
//                'customer_id' => $customerId,
//                'ongoing_orders' => $orders->ongoing_orders ?? 0,
//                'completed_orders' => $orders->completed_orders ?? 0,
//                'total_spent' => $orders->total_spent ?? 0.0,
//            ]);

        }


        Log::info('Admin ' . $admin->email . ' retrieved all users successfully.', [
            'admin_id' => $admin->id,
            'search_query' => $search,
            'total_users_found' => $users->count(),
        ]);


        return response()->json([
            'message' => 'All users retrieved successfully.',
            'customers' => $users
        ], 200);
    }

    public function deleteUser($id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $user = Customer::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->delete();
        Log::info('Admin deleted user successfully: Admin ID - ' . $admin->id . ', User ID - ' . $id);

        return response()->json(['message' => 'User successfully deleted.'], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $user = Customer::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validatedData = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|unique:customers,email_address,' . $user->C_ID,
            'tel_no' => 'nullable|digits_between:7,15',
            'shipping_address' => 'nullable|string|max:255',
        ]);

        $filteredData = array_filter($validatedData);
        $user->update($filteredData);

        Log::info('Admin updated user successfully: Admin ID - ' . $admin->id . ', User ID - ' . $id);

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user,
        ], 200);
    }

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
        Log::info('Dashboard statistics retrieved successfully by admin: ' . $admin->email);

        return response()->json([
            'message' => 'Dashboard statistics retrieved successfully.',
            'stats' => $stats,
        ], 200);
    }

}
