<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use App\Data\Auth\StoreDeviceTokenData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Response\MessageResponseData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Authentication')]
class DeviceTokenController extends Controller
{
    #[Authenticated]
    #[BodyParam('token', 'string', 'The FCM device token.', required: true, example: 'fcm-device-token-abc')]
    #[BodyParam('platform', 'string', 'The device platform (android or ios).', required: false, example: 'android')]
    #[Endpoint('Register Device Token', 'Register an FCM device token for push notifications.')]
    #[Response(['message' => 'Device token registered.'], 201, 'Device token registered successfully.')]
    /** @return MessageResponseData */
    public function store(StoreDeviceTokenData $data, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $user->deviceTokens()->firstOrCreate(
            ['token' => $data->token],
            ['platform' => $data->platform],
        );

        return response()->json(MessageResponseData::from([
            'message' => 'Device token registered.',
        ])->toArray(), HttpResponse::HTTP_CREATED);
    }

    #[Authenticated]
    #[Endpoint('Remove Device Token', 'Remove a registered FCM device token.')]
    #[Response(['message' => 'Device token removed.'], 200, 'Device token removed successfully.')]
    /** @return MessageResponseData */
    public function destroy(DeviceToken $deviceToken, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($deviceToken->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $deviceToken->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Device token removed.',
        ])->toArray());
    }
}
