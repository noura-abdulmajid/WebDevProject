<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Http\Controllers\Controller;

class AdminAuthController extends Controller
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
        $admin = AdminUser::where('email', $credentials['email'])->first();

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
            'admin' => $admin->only(['id', 'username', 'email', 'role', 'first_name', 'surname']),
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
}
