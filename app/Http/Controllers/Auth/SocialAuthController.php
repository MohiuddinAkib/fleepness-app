<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Data\UserData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;
use App\Data\Response\Auth\AuthTokenResponseData;

#[Group('Authentication')]
class SocialAuthController extends Controller
{
    #[Endpoint('OAuth Redirect', 'Redirect the user to the OAuth provider\'s authorization page.')]
    #[Response('Redirects to OAuth provider.', 302, 'Redirect to provider.')]
    #[Unauthenticated]
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    #[Endpoint('OAuth Callback', 'Handle the OAuth provider callback and issue an authentication token.')]
    #[Response(['data' => ['id' => 1, 'name' => 'John Doe'], 'token' => '1|abc123'], 200, 'Social login successful.')]
    #[Unauthenticated]
    /** @return AuthTokenResponseData */
    public function callback(string $provider): JsonResponse|Responsable
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'phone_number' => 'social_'.$socialUser->getId(),
            ],
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(AuthTokenResponseData::from([
            'message' => 'Social login successful.',
            'token' => $token,
            'user' => UserData::fromModel($user),
        ])->toArray());
    }
}
