<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AdminUserController extends Controller
{
    protected function validateAdminToken()
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                Log::warning('No token provided for admin validation.');
                return response()->json(['error' => 'Token not provided'], 401);
            }

            $admin = auth('admin_api')->user();
            if (!$admin) {
                Log::warning('Invalid token or admin not authenticated.');
                return response()->json(['error' => 'Invalid token or admin not authenticated.'], 401);
            }

            return $admin;
        } catch (TokenInvalidException $e) {
            Log::warning('Invalid token during admin validation: ' . $e->getMessage());
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Exception $e) {
            Log::error('Admin validation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while validating admin token'], 500);
        }
    }

    public function index()
    {
        try {
            Log::info('Admin users list request received');
            
            $admin = $this->validateAdminToken();
            if ($admin instanceof \Illuminate\Http\JsonResponse) {
                Log::warning('Token validation failed', ['response' => $admin->getData()]);
                return $admin;
            }

            Log::info('Admin authenticated', [
                'admin_id' => $admin->A_ID,
                'username' => $admin->username,
                'role' => $admin->role
            ]);

            if (!$admin->isSuperAdmin()) {
                Log::warning('Unauthorized access attempt to admin users list', [
                    'admin_id' => $admin->A_ID,
                    'role' => $admin->role
                ]);
                return response()->json(['error' => 'Unauthorized: Only super admin can access this resource.'], 403);
            }

            Log::info('Fetching admin users list');
            $admins = AdminUser::select('A_ID', 'username', 'email', 'first_name', 'surname', 'date_joined', 'role', 'status')
                ->get();

            Log::info('Admin users retrieved successfully', [
                'admin_id' => $admin->A_ID,
                'total_admins' => $admins->count(),
                'admins' => $admins->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => $admins,
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving admin users: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function store(Request $request)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$admin->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized: Only super admin can create new admin users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:admin_user',
            'email' => 'required|email|unique:admin_user',
            'password' => 'required|string|min:6',
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'role' => 'required|in:super_admin,editor,moderator',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $adminUser = AdminUser::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'role' => $request->role,
            'status' => 'active',
            'date_joined' => now(),
        ]);

        Log::info('Super admin created new admin user', [
            'admin_id' => $admin->A_ID,
            'new_admin_id' => $adminUser->A_ID,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully.',
            'data' => $adminUser,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$admin->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized: Only super admin can update admin users.'], 403);
        }

        $adminUser = AdminUser::find($id);
        if (!$adminUser) {
            return response()->json(['error' => 'Admin user not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:admin_user,username,' . $id . ',A_ID',
            'email' => 'sometimes|email|unique:admin_user,email,' . $id . ',A_ID',
            'password' => 'sometimes|string|min:6',
            'first_name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'role' => 'sometimes|in:super_admin,editor,moderator',
            'status' => 'sometimes|in:active,suspended,disabled',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $updateData = $request->all();
        if (isset($updateData['password'])) {
            $updateData['password'] = Hash::make($updateData['password']);
        }

        $adminUser->update($updateData);

        Log::info('Super admin updated admin user', [
            'admin_id' => $admin->A_ID,
            'updated_admin_id' => $id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin user updated successfully.',
            'data' => $adminUser,
        ]);
    }

    public function destroy($id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$admin->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized: Only super admin can delete admin users.'], 403);
        }

        $adminUser = AdminUser::find($id);
        if (!$adminUser) {
            return response()->json(['error' => 'Admin user not found.'], 404);
        }

        // Prevent self-deletion
        if ($adminUser->A_ID === $admin->A_ID) {
            return response()->json(['error' => 'You cannot delete your own account.'], 403);
        }

        $adminUser->delete();

        Log::info('Super admin deleted admin user', [
            'admin_id' => $admin->A_ID,
            'deleted_admin_id' => $id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin user deleted successfully.',
        ]);
    }
} 