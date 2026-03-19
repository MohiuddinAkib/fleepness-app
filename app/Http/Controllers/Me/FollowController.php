<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\VendorProfileData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Container\Attributes\CurrentUser;

class FollowController extends Controller
{
    public function following(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfiles = $user->following()->get();

        return VendorProfileData::collect($vendorProfiles, DataCollection::class);
    }
}
