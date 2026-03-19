<?php

declare(strict_types=1);

namespace App\Http\Controllers\user;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;

class UserVendorFollowController extends Controller
{
    public function follow(User $vendor): JsonResponse|Responsable
    {
        $userId = auth()->id();

        $existingFollow = Follower::where('follower_id', $userId)
            ->where('vendor_id', $vendor->getKey())
            ->exists();

        if ($existingFollow) {
            return response()->json([
                'message' => 'You are already following this vendor.',
            ], 400);
        }

        Follower::create([
            'follower_id' => $userId,
            'vendor_id' => $vendor->getKey(),
        ]);

        return response()->json([
            'message' => 'Vendor followed successfully.',
        ], 201);
    }

    public function unfollow(User $vendor): JsonResponse|Responsable
    {
        $userId = auth()->id();

        $follow = Follower::where('follower_id', $userId)
            ->where('vendor_id', $vendor->getKey())
            ->first();

        if (! $follow) {
            return response()->json([
                'message' => 'You are not following this vendor.',
            ], 400);
        }

        $follow->delete();

        return response()->json([
            'message' => 'Vendor unfollowed successfully.',
        ]);
    }

    // Get all vendors the logged-in user is following
    public function following(Request $request)
    {
        $user_id = auth()->id();

        // Fetch all vendors the user is following
        $following = Follower::with('vendor') // eager load vendor info
            ->where('follower_id', $user_id)
            ->get();

        // Transform data
        $followingData = $following->map(function ($item) {
            $vendor = $item->vendor;

            return [
                'id' => $vendor->id,
                'name' => $vendor->name ?? $vendor->shop_name,
                'email' => $vendor->email,
                'banner_image' => $vendor->banner_image ? asset($vendor->banner_image) : null,
                'cover_img' => $vendor->cover_img ? asset($vendor->cover_img) : null,
                'followed_at' => $item->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'following' => $followingData,
        ]);
    }

    // Get all users following the logged-in user
    public function followers(Request $request)
    {
        $user_id = auth()->id();

        // Fetch all followers of the logged-in user
        $followers = Follower::with('follower') // eager load follower info
            ->where('vendor_id', $user_id)
            ->get();

        // Transform data
        $followersData = $followers->map(function ($item) {
            $follower = $item->follower;

            return [
                'id' => $follower->id,
                'name' => $follower->name,
                'email' => $follower->email,
                'profile_img' => $follower->profile_img ? asset($follower->profile_img) : null,
                'followed_at' => $item->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'followers' => $followersData,
        ]);
    }
}
