<?php

namespace App\Http\Controllers\Admin;

use App\Models\Products;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessImageUpload;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    // Cache duration in minutes
    const CACHE_DURATION = 60;

    public function getProducts(Request $request)
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        // Get pagination parameters, default to 10 items per page
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        // Generate cache key based on request parameters
        $cacheKey = "products:page:{$page}:per_page:{$perPage}";

        // Try to get data from cache first
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            Log::info('Products retrieved from cache');
            return response()->json($cachedData, 200);
        }

        // If not in cache, fetch from database
        $products = Products::select('P_ID', 'p_name', 'gender_target', 'description', 'categories', 'colours', 'photo', 'price', 'overall_stock_status')
            ->with(['inventory' => function ($query) {
                $query->select('P_ID', 'color', 'price', 'size', 'stock_level', 'stock_status');
            }])
            ->paginate($perPage, ['*'], 'page', $page);

        // Get gender counts
        $genderCounts = Products::select('gender_target', DB::raw('count(*) as count'))
            ->groupBy('gender_target')
            ->get()
            ->pluck('count', 'gender_target')
            ->toArray();

        $responseData = [
            'message' => 'Products retrieved successfully.',
            'products' => $products->items(),
            'gender_counts' => $genderCounts,
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ];

        // Store in cache
        Cache::put($cacheKey, $responseData, self::CACHE_DURATION);

        Log::info('Products retrieved from database and cached');

        return response()->json($responseData, 200);
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

        try {
            DB::beginTransaction();

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

            // Convert arrays to JSON strings
            $validatedData['categories'] = json_encode($validatedData['categories']);
            $validatedData['colours'] = json_encode($validatedData['colours']);

            $product = Products::create($validatedData);

            // Use bulk insert for inventory
            $inventoryData = array_map(function($item) use ($product) {
                return [
                    'P_ID' => $product->P_ID,
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'stock_level' => $item['stock_level'],
                    'stock_status' => $item['stock_status'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $validatedData['inventory']);

            Inventory::insert($inventoryData);

            DB::commit();

            // Clear products cache
            Cache::tags(['products'])->flush();

            Log::info('Product added by admin: ' . $admin->email);

            return response()->json([
                'message' => 'Product added successfully.',
                'product' => $product->load('inventory'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add product'], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $admin = $this->validateAdminToken();
            if ($admin instanceof \Illuminate\Http\JsonResponse) {
                return $admin;
            }

            if (!$this->hasAdminRole($admin)) {
                return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
            }

            Log::info('Received request payload for updating product', $request->all());

            // Validate product ID
            if (!is_numeric($id)) {
                return response()->json([
                    'message' => 'Invalid product ID.',
                    'error' => 'Product ID must be numeric'
                ], 400);
            }

            // Check if product exists
            $product = Products::where('P_ID', $id)->first();
            if (!$product) {
                Log::error('Product not found', ['product_id' => $id]);
                return response()->json([
                    'message' => 'Product not found.',
                    'error' => 'No product found with ID: ' . $id
                ], 404);
            }

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

            // Check if colors have changed
            if ($request->has('colours')) {
                // Get old colors, ensuring it's an array
                $oldColours = is_string($product->colours) 
                    ? json_decode($product->colours, true) ?? []
                    : (is_array($product->colours) ? $product->colours : []);
                
                $newColours = $request->colours;
                
                // Find removed colors
                $removedColours = array_diff($oldColours, $newColours);
                
                // Find added colors
                $addedColours = array_diff($newColours, $oldColours);
                
                // Delete inventory records for removed colors
                if (!empty($removedColours)) {
                    Inventory::where('P_ID', $product->P_ID)
                        ->whereIn('color', $removedColours)
                        ->delete();
                }
                
                // Log color changes
                Log::info('Color changes detected', [
                    'product_id' => $product->P_ID,
                    'old_colors' => $oldColours,
                    'new_colors' => $newColours,
                    'removed_colors' => $removedColours,
                    'added_colors' => $addedColours
                ]);
            }
            
            // Convert arrays to JSON strings before updating
            $updateData = $validatedData;
            if (isset($updateData['categories'])) {
                $updateData['categories'] = json_encode($updateData['categories']);
            }
            if (isset($updateData['colours'])) {
                $updateData['colours'] = json_encode($updateData['colours']);
            }
            
            // Remove inventory from update data as it's handled separately
            unset($updateData['inventory']);
            
            DB::beginTransaction();
            
            try {
                // Update product
                $product->update($updateData);
                
                // Handle inventory updates
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

                    // Delete inventory records that are not in the new list
                    Inventory::where('P_ID', $product->P_ID)
                        ->whereNotIn(\DB::raw("CONCAT(color, '_', size)"), $newInventoryKeys)
                        ->delete();
                }
                
                DB::commit();
                
                Log::info('Product updated successfully', [
                    'product_id' => $product->P_ID,
                    'update_data' => $updateData
                ]);
                
                return response()->json([
                    'message' => 'Product updated successfully.',
                    'product' => $product->load('inventory'),
                ], 200);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to update product: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                    'product_id' => $product->P_ID,
                    'update_data' => $updateData
                ]);
                return response()->json([
                    'message' => 'Failed to update product.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Error in updateProduct: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'An error occurred while updating the product.',
                'error' => $e->getMessage()
            ], 500);
        }
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

    /**
     * Upload product image
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
            Log::info('Starting image upload process', [
                'original_name' => $request->file('image')->getClientOriginalName(),
                'file_size' => $request->file('image')->getSize(),
                'mime_type' => $request->file('image')->getMimeType()
            ]);

            if (!$request->hasFile('image')) {
                return response()->json(['error' => 'No image file provided'], 400);
            }

            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newFileName = time() . '_' . uniqid() . '.' . $extension;

            // Store the file
            $path = $file->storeAs('product/images', $newFileName, 'public');

            Log::info('File stored successfully', ['file_path' => $path]);

            // Return the path
            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Failed to upload image: ' . $e->getMessage()], 500);
        }
    }

    protected function hasAdminRole($admin)
    {
        return $admin->role === 'super_admin' || $admin->role === 'editor';
    }
}