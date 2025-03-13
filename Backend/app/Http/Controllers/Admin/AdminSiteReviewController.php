<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AdminSiteReviewController extends Controller
{
    public function getSiteReview(): JsonResponse
    {
        Log::info('Site Review Retrieved Successfully');

        $siteReviews = SiteReview::all();

        return response()->json([
            'success' => true,
            'data' => $siteReviews,
        ]);
    }
}