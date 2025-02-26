<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomerProfileController extends Controller {
    public function getProfile()
    {
        try {
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
                'email_address' => 'nullable|email|unique:customers,email_address,' . $user->C_ID,
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
}
