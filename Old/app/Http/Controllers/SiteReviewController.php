<?php

namespace App\Http\Controllers;

use App\Models\SiteReview;
use Illuminate\Http\Request;

class SiteReviewController extends Controller
{
    public function create()
    {
        return view('contact.site-review-page');
    }


    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'review' => ['required'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        $review = SiteReview::create([
            'review_email' => $attributes['email'],
            'review' => $attributes['review'],
            'rating' => $attributes['rating'],
        ]);

        info('New site review submitted', [
            'review_email' => $review->review_email,
            'rating' => $review->rating,
            'review' => $review->review,
        ]);

        return response()->json([
            'message' => 'Thank you for your review!',
            'data' => [
                'email' => $review->review_email,
                'rating' => $review->rating,
                'review' => $review->review,
            ]
        ], 200);
    }

    public function confirm()
    {
        return view('contact.review-confirmation');
    }
}
