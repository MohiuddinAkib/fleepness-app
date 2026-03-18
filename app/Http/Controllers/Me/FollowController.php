<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Attributes\CurrentUser;
use App\Data\VendorProfileData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class FollowController extends Controller
{
    public function following(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfiles = $user->following()->get();

        return Response::json([
            'data' => VendorProfileData::collect($vendorProfiles),
        ]);
    }
}
