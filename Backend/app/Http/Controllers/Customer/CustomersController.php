<?php

namespace App\Http\Controllers\Customer;

use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


class CustomersController extends Controller
{
    public function login(Request $request)
    {
        info('Login Request Received: Email - ' . $request->email);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            info('Validation Failed: ', $validator->errors()->toArray());
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = [
            'email_address' => $request->email,
            'password' => $request->password,
        ];

        $user = Customer::where('email_address', $credentials['email_address'])->first();
        if (!$user) {
            info('User Not Found: Email - ' . $credentials['email_address']);
            return response()->json(['error' => 'The account does not exist.'], 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            info('Password Mismatch: Email - ' . $credentials['email_address']);
            return response()->json(['error' => 'Invalid email or password.'], 401);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            info('JWT Token Generation Failed: Email - ' . $credentials['email_address']);
            return response()->json(['error' => 'Unable to authenticate the user.'], 401);
        }

        info('User Login Successful: Email - ' . $credentials['email_address']);
        info('User Login Successful Response: ', [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user->only(['C_ID', 'first_name', 'surname', 'email_address', 'tel_no', 'shipping_address','billing_address']),
        ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user->only(['C_ID', 'first_name', 'surname', 'email_address', 'tel_no', 'shipping_address','billing_address']),
        ]);

    }

    public function register(Request $request)
    {
        info('Register Request Data:', $request->all());

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email_address' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Customer::create([
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'email_address' => $request->email_address,
            'password' => bcrypt($request->password),
        ]);

        info('New Customer Registered:', [
            'first_name' => $user->first_name,
            'surname' => $user->surname,
            'email_address' => $user->email_address,
            'time' => now(),
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Customer registered successfully',
            'user' => $user->only(['C_ID', 'first_name', 'surname', 'email_address']),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 201);
    }

    public function logout()
    {
        try {
            Log::info('Attempting to logout user...');

            $token = JWTAuth::getToken();
            if (!$token) {
                Log::warning('No token provided');
                return response()->json(['message' => 'Token not provided'], 400);
            }

            if (!auth('api')->user()) {
                Log::warning('Invalid token or user not authenticated.');
                return response()->json(['message' => 'Invalid token or user not authenticated.'], 401);
            }

            JWTAuth::invalidate($token);

            Log::info('User successfully logged out');
            return response()->json(['message' => 'Successfully logged out'], 200);

        } catch (TokenInvalidException $e) {
            Log::warning('Token invalid: ' . $e->getMessage());
            return response()->json(['message' => 'Token is invalid'], 401);
        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while trying to logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
