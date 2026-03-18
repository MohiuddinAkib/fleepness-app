<?php

namespace App\Http\Controllers\user;

use App\Models\User;
use App\Models\VendorReview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class UserVendorReviewController extends Controller
{
    public function store(Request $request, User $vendor): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = VendorReview::create([
            'vendor_id' => $vendor->getKey(),
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review,
        ], 201);
    }

    public function index(User $vendor): JsonResponse
    {
        $reviews = VendorReview::where('vendor_id', $vendor->getKey())->get();

        return response()->json([
            'vendor' => $vendor,
            'reviews' => $reviews,
        ]);
    }

    public function destroy(VendorReview $review): JsonResponse
    {
        abort_unless($review->user_id === auth()->id(), 403, 'Unauthorized');

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
