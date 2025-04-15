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
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        $siteReviews = SiteReview::all();

        $totalRead = $siteReviews->where('is_read', true)->count();
        $totalUnread = $siteReviews->where('is_read', false)->count();
        $totalReplied = $siteReviews->where('is_replied', true)->count();
        $totalUnreplied = $siteReviews->where('is_replied', false)->count();

        Log::info('Admin retrieved site reviews', [
            'admin_id' => $admin->id,
            'total_reviews' => $siteReviews->count(),
        ]);

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
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        Log::info('Marking site review as read', [
            'admin_id' => $admin->id,
            'site_review_id' => $id,
            'request_data' => $request->all(),
        ]);

        $siteReview = SiteReview::findOrFail($id);

        if ($request->has('is_read')) {
            $siteReview->is_read = $request->boolean('is_read');
        }

        $siteReview->save();

        Log::info('Site review marked as read', [
            'admin_id' => $admin->id,
            'site_review_id' => $id,
            'is_read' => $siteReview->is_read,
        ]);

        return response()->json([
            'success' => true,
            'message' => $siteReview->is_read ? 'Review marked as read.' : 'Review marked as unread.',
            'data' => $siteReview,
        ]);
    }

    public function markAsReplied(Request $request, $id): JsonResponse
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        Log::info('Marking site review as replied', [
            'admin_id' => $admin->id,
            'site_review_id' => $id,
            'request_data' => $request->all(),
        ]);

        $siteReview = SiteReview::findOrFail($id);

        if ($request->has('is_replied')) {
            $siteReview->is_replied = $request->boolean('is_replied');
        }

        if ($request->has('reply')) {
            $siteReview->reply = $request->input('reply');
        }
        $siteReview->save();

        Log::info('Site review marked as replied', [
            'admin_id' => $admin->id,
            'site_review_id' => $id,
            'is_replied' => $siteReview->is_replied,
        ]);

        return response()->json([
            'success' => true,
            'message' => $siteReview->is_replied ? 'Review marked as replied.' : 'Review marked as unreplied.',
            'data' => $siteReview,
        ]);
    }

    public function sendReply(Request $request, $id): JsonResponse
    {
        $admin = $this->validateAdminToken();
        if ($admin instanceof \Illuminate\Http\JsonResponse) {
            return $admin;
        }

        if (!$this->hasAdminRole($admin)) {
            return response()->json(['error' => 'Unauthorized: You do not have the required admin role.'], 403);
        }

        Log::info('Sending reply to site review', [
            'admin_id' => $admin->id,
            'site_review_id' => $id,
        ]);

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

        Log::info('Reply sent to site review', [
            'admin_id' => $admin->id,
            'site_review_id' => $id,
            'has_email' => !empty($siteReview->review_email),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reply sent and email delivered (if email exists).',
            'data' => $siteReview,
        ]);
    }


}