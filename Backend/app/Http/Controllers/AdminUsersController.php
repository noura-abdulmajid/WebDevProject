<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminUsers;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
    private function hasAdminRole($admin, $allowedRoles = [AdminUsers::ROLE_ADMIN, AdminUsers::ROLE_SUPER_ADMIN]): bool
    {
        return in_array(optional($admin)->role, $allowedRoles);
    }
}
