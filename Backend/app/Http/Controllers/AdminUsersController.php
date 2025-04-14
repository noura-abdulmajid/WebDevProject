<?php

namespace App\Http\Controllers;

use App\Mail\MessageResponse;
use App\Mail\OrderShipped;
use App\Mail\RefundConfirmation;
use App\Mail\ReturnRejection;
use App\Models\Customer;
use App\Models\DiscountCode;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\ProductReturn;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminUsers;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Products;

class AdminUsersController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Admin Login Request Received.......');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation Failed In Login.', $validator->errors()->toArray());
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        $admin = AdminUsers::where('email', $credentials['email'])->first();

        if (!$admin) {
            Log::warning('Admin Not Found: ' . $credentials['email']);
            return response()->json(['error' => 'The admin account does not exist.'], 404);
        }

        if (!Hash::check($credentials['password'], $admin->password)) {
            Log::warning('Admin Password Mismatch: ' . $credentials['email']);
            return response()->json(['error' => 'Invalid email or password.'], 401);
        }

        if (!$token = auth('admin_api')->attempt($credentials)) {
            Log::warning('Failed To Generate JWT Token: ' . $credentials['email']);
            return response()->json(['error' => 'Unable to authenticate the admin.'], 401);
        }

        Log::info('Admin Login Successful: ' . $credentials['email']);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('admin_api')->factory()->getTTL() * 60,
            'admin' => $admin->only(['id', 'username', 'email', 'role', 'first_name', 'surname',
            ]),
        ]);
    }

    public function logout()
    {
        try {
            Log::info('Admin attempting to logout...');

            $token = JWTAuth::getToken();
            if (!$token) {
                Log::warning('No token provided for admin logout.');
                return response()->json(['message' => 'Token not provided'], 400);
            }

            $admin = auth('admin_api')->user();
            if (!$admin) {
                Log::warning('Invalid token or admin not authenticated.');
                return response()->json(['message' => 'Invalid token or admin not authenticated.'], 401);
            }

            JWTAuth::invalidate($token);

            Log::info('Admin successfully logged out: ' . $admin->email);
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (TokenInvalidException $e) {
            Log::warning('Invalid token during admin logout: ' . $e->getMessage());
            return response()->json(['message' => 'Token is invalid'], 401);
        } catch (\Exception $e) {
            Log::error('Admin logout failed: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong while trying to logout', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllUsers(Request $request)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $users = Customer::select('C_ID', 'first_name', 'surname', 'email_address', 'tel_no', 'shipping_address')
            ->paginate(10);

        Log::info('All users retrieved successfully by admin: ' . $admin->email);

        return response()->json([
            'message' => 'All users retrieved successfully.',
            'users' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
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

    public function getAllReturnRequests()
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

    public function returnAllOrders()
    {

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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
                $deliveryDate = (new dateTime())->modify('+3 days')->format('jS F, Y');

                // email shipping confirmation
                Mail::to($customer->email_address)->queue(new OrderShipped($order, $deliveryDate));

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

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

    public function returnAllMessages()
    {

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        Log::info("Returning all return requests");

        $messages = Message::select(
            'M_ID',
            'first_name',
            'last_name',
            'email',
            'message')->paginate(10);

        return response()->json([
            'message' => 'All return requests retrieved successfully',
            'total_pages' => $messages->total(),
            'current_page' => $messages->currentPage(),
            'last_page' => $messages->lastPage(),
            'return_requests' => $messages,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function respondToMessage(Request $request)
    {

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        try {
            Log::info('[Contact-Us] Received message response request data: ', $request->all());

            $attributes = $request->validate([
                'M_ID' => ['required', 'integer', 'exists:messages,M_ID'],
                'response' => ['required', 'string'],
            ]);

            $message = Message::where('M_ID', $attributes['M_ID'])->first();

            $message->response = $attributes['response'];
            $message->save();
            // email response to user

            Mail::to($message->email)->queue(new MessageResponse($message));

            return response()->json([
                'success' => true,
                'message' => 'Response sent successfully',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
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

    public function returnAllDiscountCodes()
    {

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $allDiscountCodes = DiscountCode::all();

        return response()->json([
            'success' => true,
            'message' => 'all discount codes found successfully',
            'discount_codes' => $allDiscountCodes
        ]);
    }

    public function createNewDiscountCode(Request $request)
    {

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

    public function destroyDiscountCode(Request $request): JsonResponse
    {

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

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

    private function validateAdminToken()
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 400);
            }

            $admin = auth('admin_api')->setToken($token)->user();
            if (!$admin) {
                return response()->json(['error' => 'Invalid token or admin not authenticated.'], 401);
            }

            return $admin;
        } catch (TokenInvalidException $e) {
            Log::warning('Token invalid: ' . $e->getMessage());
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            Log::error('Failed to authenticate token: ' . $e->getMessage());
            return response()->json(['error' => 'Token authentication failed'], 500);
        }
    }

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

    private function hasAdminRole($admin, $allowedRoles = [AdminUsers::ROLE_ADMIN, AdminUsers::ROLE_SUPER_ADMIN]): bool
    {
        return in_array(optional($admin)->role, $allowedRoles);
    }
}
