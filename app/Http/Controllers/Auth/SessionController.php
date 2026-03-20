<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Data\Response\MessageResponseData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Authentication')]
class SessionController extends Controller
{
    #[Authenticated]
    #[Endpoint('Logout', 'Revoke the current access token and log the user out.')]
    #[Response(['message' => 'Logged out.'], 200, 'Successfully logged out.')]
    /** @return MessageResponseData */
    public function destroy(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $user->currentAccessToken()?->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Logged out successfully.',
        ])->toArray());
    }
}
