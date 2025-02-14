<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Models\Customer;

class ForgetPasswordController extends Controller
{
    protected function createResetToken($email)
    {
        $token = Str::random(64);

        PasswordReset::updateOrCreate(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        return $token;
    }

    public function forgotPassword(Request $request)
    {
        Log::info('Entering forgotPassword method.');
        $rawEmail = $request->input('email');
        Log::info('Raw email from request (Before): ' . $rawEmail);

        $validatedData = $request->validate([
            'email' => 'required|email|exists:customers,email_address',
        ]);

        $email = $validatedData['email'];
        Log::info('The validated email is (After): ' . $email);
        $token = $this->createResetToken($email);
        $baseUrl = env('FORGOT_URL', 'http://localhost:5173');
        $resetUrl = "{$baseUrl}/reset-password?token={$token}&email={$email}";

        try {
            Log::info('Attempting to send email to: ' . $email);

            Mail::send('emails.password-reset', ['resetUrl' => $resetUrl], function ($message) use ($email) {
                $message->to($email)->subject('Reset Your Password');
            });

            Log::info('Password reset email sent successfully to: ' . $email);

            return response()->json(['message' => 'Password reset link sent successfully.'], 200);
        } catch (\Exception $exception) {
            Log::error('Failed to send password reset email to: ' . $email . '. Error: ' . $exception->getMessage());

            return response()->json(['message' => 'Failed to send password reset email. Please try again later.'], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        Log::info('Received data from front-end:', $request->all());

        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:customers,email_address',
                'token' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);

            Log::info('[ResetPassword] Validation passed for email: ' . $validatedData['email']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('[ResetPassword] Validation error:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }

        $resetRecord = PasswordReset::where('email', $validatedData['email'])
            ->where('token', $validatedData['token'])
            ->first();
        if ($resetRecord) {
            Log::info('[ResetPassword] Found matching PasswordReset record: ', ['email' => $validatedData['email'], 'token' => $validatedData['token']]);
        } else {
            Log::warning('[ResetPassword] No matching PasswordReset record found or token is invalid.', [
                'email' => $validatedData['email'],
                'token' => $validatedData['token']
            ]);
            return response()->json(['message' => 'Token is invalid or expired.'], 400);
        }


        if (!$resetRecord || now()->diffInMinutes($resetRecord->created_at) > 60) {
            return response()->json(['message' => 'Token is invalid or expired.'], 400);
        }

        $customer = Customer::where('email_address', $validatedData['email'])->first();
        $customer->password = Hash::make($validatedData['password']);
        $customer->save();

        $resetRecord->delete();

        return response()->json(['message' => 'Password reset successfully.'], 200);
    }
}
