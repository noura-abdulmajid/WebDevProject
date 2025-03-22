<?php

namespace App\Http\Controllers\Admin;

use App\Models\Products;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        $products = Products::select('P_ID', 'p_name', 'gender_target', 'description', 'categories', 'colours', 'photo', 'price', 'overall_stock_status')
            ->with(['inventory' => function ($query) {
                $query->select('P_ID', 'color', 'price', 'size', 'stock_level', 'stock_status');
            }])
            ->get();

        Log::info('All products retrieved successfully by admin: ' . $admin->email);

        return response()->json([
            'message' => 'All products retrieved successfully.',
            'products' => $products,
        ], 200);
    }

    public function addProduct(Request $request)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $validatedData = $request->validate([
            'p_name' => 'required|string',
            'gender_target' => 'required|string',
            'description' => 'required|string',
            'categories' => 'required|array',
            'colours' => 'required|array',
            'photo' => 'required|string',
            'price' => 'required|numeric',
            'overall_stock_status' => 'required|string',
            'inventory' => 'required|array',
        ]);

        $product = Products::create($validatedData);

        foreach ($validatedData['inventory'] as $inventory) {
            Inventory::create([
                'P_ID' => $product->P_ID,
                'color' => $inventory['color'],
                'size' => $inventory['size'],
                'stock_level' => $inventory['stock_level'],
                'stock_status' => $inventory['stock_status'],
            ]);
        }

        Log::info('Product added by admin: ' . $admin->email);

        return response()->json([
            'message' => 'Product added successfully.',
            'product' => $product->load('inventory'),
        ], 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }
        Log::info('Received request payload for updating product', $request->all());

        $validatedData = $request->validate([

            'p_name' => 'sometimes|string',
            'gender_target' => 'sometimes|string',
            'description' => 'sometimes|string',
            'categories' => 'sometimes|array',
            'colours' => 'sometimes|array',
            'photo' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'overall_stock_status' => 'sometimes|string',

            'inventory' => 'sometimes|array',
            'inventory.*.color' => 'required|string',
            'inventory.*.size' => 'required|string',
            'inventory.*.price' => 'required|numeric',
            'inventory.*.stock_level' => 'required|integer',
            'inventory.*.stock_status' => 'required|string',
        ]);

        $product = Products::findOrFail($id);
        $product->update($validatedData);


        if ($request->has('inventory')) {
            $newInventoryKeys = [];

            foreach ($request->inventory as $inventory) {
                $inventoryKey = "{$inventory['color']}_{$inventory['size']}";
                $newInventoryKeys[] = $inventoryKey;

                $existingInventory = Inventory::where('P_ID', $product->P_ID)
                    ->where('color', $inventory['color'])
                    ->where('size', $inventory['size'])
                    ->first();

                if ($existingInventory) {

                    $existingInventory->update([
                        'stock_level' => $inventory['stock_level'],
                        'stock_status' => $inventory['stock_status'],
                        'price' => $inventory['price'],
                    ]);
                } else {
                    Inventory::create([
                        'P_ID' => $product->P_ID,
                        'color' => $inventory['color'],
                        'size' => $inventory['size'],
                        'stock_level' => $inventory['stock_level'],
                        'stock_status' => $inventory['stock_status'],
                        'price' => $inventory['price'],
                    ]);
                }
            }

            Inventory::where('P_ID', $product->P_ID)
                ->whereNotIn(\DB::raw("CONCAT(color, '_', size)"), $newInventoryKeys)
                ->delete();
        }

        Log::info('Product updated by admin: ' . $admin->email);

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product->load('inventory'),
        ], 200);
    }

    public function deleteProduct($id)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $product = Products::findOrFail($id);
        $product->inventory()->delete();
        $product->delete();

        Log::info('Product deleted by admin: ' . $admin->email);

        return response()->json([
            'message' => 'Product deleted successfully.',
        ], 200);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('product/images', 'public');
            $imageUrl = url(Storage::url($filePath));


            $response = [
                'message' => 'Image uploaded successfully.',
                'image_url' => $imageUrl,
            ];

            Log::info('Image upload response:', $response);

            return response()->json([
                'message' => 'Image uploaded successfully.',
                'image_url' => $imageUrl,
            ], 200);
        }


        return response()->json([
            'message' => 'No image file uploaded.',
        ], 400);
    }


}