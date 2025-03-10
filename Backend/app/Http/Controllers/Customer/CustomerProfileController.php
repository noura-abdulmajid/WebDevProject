<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CustomerProfileController extends Controller {
    public function getProfile(Request $request)
    {
        try {
            Log::info('Authorization Token:', ['token' => request()->header('Authorization')]);

            $user = $this->validateCustomerToken();
            if ($user instanceof JsonResponse) {
                return $user;
            }

            Log::info('Customer profile retrieved successfully: ' . $user->email_address);

            return response()->json([
                'message' => 'Customer profile retrieved successfully.',
                'user' => $user->only(['C_ID', 'first_name', 'surname', 'email_address', 'tel_no', 'shipping_address']),
            ], 200);
        } catch (\Exception $e) {
            Log::error('An error occurred while retrieving the customer profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while retrieving the customer profile.'], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            // Log the beginning of the profile update process
            Log::info('Attempting to update customer profile...');
            Log::info('Received request data:', $request->all());
            Log::info('Authorization Token:', ['token' => request()->header('Authorization')]);

            // Validate the customer token and retrieve the authenticated user
            $user = $this->validateCustomerToken();
            if ($user instanceof JsonResponse) {
                return $user; // Return error response if validation fails
            }
            Log::info('Raw JSON data:', ['data' => $request->getContent()]);


            // Ensure the resolved user is an instance of the Customer model
            if (!($user instanceof \App\Models\Customer)) {
                Log::info('Resolved user is not an instance of Customer.', [
                    'actual_class' => is_object($user) ? get_class($user) : gettype($user),
                    'user_details' => $user
                ]);

                return response()->json(['error' => 'Invalid customer instance.'], 500);
            }

            // Validate incoming request data
            $validatedData = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'surname' => 'nullable|string|max:255',
                'email_address' => 'nullable|email|unique:customers,email_address,' . $user->C_ID . ',C_ID',
                'tel_no' => 'nullable|numeric',
                'shipping_address' => 'nullable|string|max:255',
                'billing_address' => 'nullable|string|max:255',
            ]);

            // Filter out null or empty values from the validated data
            $filteredData = $this->removeEmptyFields($validatedData);

            // If no valid data is provided, return a validation error response
            if (empty($filteredData)) {
                return response()->json(['error' => 'No valid data provided for update.'], 422);
            }

            // Update the user's profile with filtered data
            $user->update($filteredData);

            // Log successful profile update
            Log::info('Customer profile updated successfully: ' . $user->email_address);

            // Return a success response with the updated user data
            return response()->json([
                'message' => 'Customer profile updated successfully.',
                'user' => $user->only(['C_ID', 'first_name', 'surname', 'email_address', 'tel_no', 'shipping_address']),
            ], 200);
        } catch (ValidationException $e) {
            // Log validation error details and return a response with the error details
            Log::warning('Validation error: ' . $e->getMessage());
            return response()->json(['error' => 'Validation error.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Log any unexpected error and return a generic error response
            Log::error('An error occurred while updating the customer profile: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while updating the customer profile.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Filter out null or empty fields from the given data.
     *
     * @param array $data
     * @return array
     */
    protected function removeEmptyFields(array $data): array
    {
        // Use array_filter to remove fields with null values
        return array_filter($data, function ($value) {
            return !is_null($value);
        });
    }

    /**
     * Retrieve orders, ordered items, and shipment details for the authenticated customer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders()
    {
        try {
            Log::info('Attempting to get orders ...');
            // Validate the customer token and retrieve the authenticated customer
            $user = $this->validateCustomerToken();
            if ($user instanceof JsonResponse) {
                return $user;
            }

            // Extract customer information (C_ID, first_name, surname) from the authenticated user
            $C_ID = $user->C_ID;
            $firstName = $user->first_name;
            $surname = $user->surname;

            // Log the retrieved customer details
            Log::info('Customer retrieved:', [
                'C_ID' => $C_ID,
                'first_name' => $firstName,
                'surname' => $surname,
            ]);

            // Query the orders table to retrieve all orders for the customer
            $orders = DB::table('orders')
                ->where('C_ID', $C_ID)
                ->get();

            // Check if any orders exist for the customer
            if ($orders->isEmpty()) {
                return response()->json([
                    'message' => 'No orders found for the customer.',
                ], 404); // Return 404 if no orders are found
            }

            // Initialize an array to store order details
            $orderDetails = [];

            // Loop through each order to collect related data (ordered items and shipment info)
            foreach ($orders as $order) {
                $O_ID = $order->O_ID;

                // Retrieve all ordered items associated with the order
                $orderedItems = DB::table('ordered_items')
                    ->where('O_ID', $O_ID)
                    ->get();

                // Retrieve shipping details associated with the order
                $shippedInfo = DB::table('shipped')
                    ->where('O_ID', $O_ID)
                    ->first();

                // Append the order details, ordered items, and shipping info to the array
                $orderDetails[] = [
                    'order' => $order,
                    'ordered_items' => $orderedItems,
                    'shipped' => $shippedInfo,
                ];
            }

            // Return a JSON response with the customer and order details
            return response()->json([
                'message' => 'Orders retrieved successfully.',
                'customer' => [
                    'C_ID' => $C_ID,
                    'first_name' => $firstName,
                    'surname' => $surname,
                ],
                'orders' => $orderDetails,
            ], 200); // Return 200 status code for success
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error retrieving orders: ' . $e->getMessage());

            // Return a JSON response with error details and a 500 status code
            return response()->json(['error' => 'An error occurred retrieving orders.'], 500);
        }
    }

}
