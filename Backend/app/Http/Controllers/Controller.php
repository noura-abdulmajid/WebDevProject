<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Log;
use App\Models\AdminUser;
use Tymon\JWTAuth\Facades\JWTAuth;




abstract class Controller
{
    //Admin JWT
    protected function validateAdminToken()
    {
        try {
            $admin = auth('admin_api')->user();
            if (!$admin) {
                return response()->json(['error' => 'Invalid token or admin not authenticated.'], 401);
            }
            return $admin;
        } catch (TokenInvalidException $e) {
            Log::warning('Token invalid: ' . $e->getMessage());
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Exception $e) {
            Log::error('Failed to authenticate token: ' . $e->getMessage());
            return response()->json(['error' => 'Token authentication failed'], 500);
        }
    }

    //Check the Admin Role
    protected function hasAdminRole($admin)
    {
        return in_array(optional($admin)->role, [AdminUser::ROLE_ADMIN, AdminUser::ROLE_SUPER_ADMIN]);
    }
    protected function validateCustomerToken()
    {
        try {
            $customer = auth('api')->user();
            if (!$customer) {
                throw new HttpResponseException(
                    response()->json(['error' => 'Invalid token or customer not authenticated.'], 401)
                );
            }
            return $customer;
        } catch (TokenInvalidException $e) {
            Log::warning('Token invalid: ' . $e->getMessage());
            throw new HttpResponseException(
                response()->json(['error' => 'Token is invalid'], 401)
            );
        } catch (\Exception $e) {
            Log::error('Failed to authenticate token: ' . $e->getMessage());
            throw new HttpResponseException(
                response()->json(['error' => 'Token authentication failed'], 500)
            );
        }
    }
}
