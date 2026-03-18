<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\DeviceToken;
use App\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Auth\StoreDeviceTokenData;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class DeviceTokenController extends Controller
{
    public function store(StoreDeviceTokenData $data, #[CurrentUser] User $user): JsonResponse
    {
        $user->deviceTokens()->firstOrCreate(
            ['token' => $data->token],
            ['platform' => $data->platform],
        );

        return Response::json(['message' => 'Device token registered.'], HttpResponse::HTTP_CREATED);
    }

    public function destroy(DeviceToken $deviceToken, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($deviceToken->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $deviceToken->delete();

        return Response::json(['message' => 'Device token removed.']);
    }
}
