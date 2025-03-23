<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\ReviewReplyMail;
use Illuminate\Support\Facades\Mail;


class AdminSiteReviewController extends Controller
{
    public function getSiteReview(): JsonResponse
    {
        $siteReviews = SiteReview::all();

        $totalRead = $siteReviews->where('is_read', true)->count();
        $totalUnread = $siteReviews->where('is_read', false)->count();
        $totalReplied = $siteReviews->where('is_replied', true)->count();
        $totalUnreplied = $siteReviews->where('is_replied', false)->count();

        return response()->json([
            'success' => true,
            'data' => $siteReviews,
            'statistics' => [
                'total_read' => $totalRead,
                'total_unread' => $totalUnread,
                'total_replied' => $totalReplied,
                'total_unreplied' => $totalUnreplied,
            ],
        ]);
    }

    public function markAsRead(Request $request, $id): JsonResponse
    {
        info('Marking site review as read', [
            'request_data' => $request->all(),
            'site_review_id' => $id,
        ]);

        $siteReview = SiteReview::findOrFail($id);


        if ($request->has('is_read')) {
            $siteReview->is_read = $request->boolean('is_read');
        }

        $siteReview->save();

        return response()->json([
            'success' => true,
            'message' => $siteReview->is_read ? 'Review marked as read.' : 'Review marked as unread.',
            'data' => $siteReview,
        ]);
    }

    public function markAsReplied(Request $request, $id): JsonResponse
    {
        info('Marking site review as replied', [
            'request_data' => $request->all(),
            'site_review_id' => $id,
        ]);

        $siteReview = SiteReview::findOrFail($id);

        if ($request->has('is_replied')) {
            $siteReview->is_replied = $request->boolean('is_replied');
        }

        if ($request->has('reply')) {
            $siteReview->reply = $request->input('reply');
        }
        $siteReview->save();

        return response()->json([
            'success' => true,
            'message' => $siteReview->is_replied ? 'Review marked as replied.' : 'Review marked as unreplied.',
            'data' => $siteReview,
        ]);
    }

    public function sendReply(Request $request, $id): JsonResponse
    {
        info('Sending reply to site review');
        $request->validate([
            'reply' => 'required|string',
        ]);

        $siteReview = SiteReview::findOrFail($id);

        $siteReview->reply = $request->reply;
        $siteReview->is_replied = true;
        $siteReview->save();

        if (!empty($siteReview->review_email)) {
            Mail::to($siteReview->review_email)->send(new ReviewReplyMail($siteReview->review, $request->reply));
        }

        return response()->json([
            'success' => true,
            'message' => 'Reply sent and email delivered (if email exists).',
            'data' => $siteReview,
        ]);
    }


}