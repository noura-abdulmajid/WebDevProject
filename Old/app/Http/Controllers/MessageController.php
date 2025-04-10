<?php

namespace App\Http\Controllers;

use App\Mail\MessageReceived;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;



class MessageController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contact.contact-page');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('[Contact-Us] Received request data: ', $request->all());
        try {
            $attributes = $request->validate([
                'first_name' => ['required'],
                'last_name' => ['required'],
                'email' => ['required', 'email'],
                //'reason' => ['nullable', Rule::in(['General Query', 'Order Query', 'Returns Query', 'Product Query', 'Other'])],
                'message' => ['required'],
            ]);

            Log::info('[Contact-Us] Validation passed. Attributes: ', $attributes);

            $message = Message::create($attributes);

            Log::info('[Contact-Us] Message created successfully.', ['message_id' => $message->id]);

            Mail::to($message->email)->queue(new MessageReceived($message));

            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully',
                'data' => $message,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function confirm()
    {
        return view('contact.message-confirmation');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
