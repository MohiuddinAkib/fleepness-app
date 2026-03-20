<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Data\UserData;
use Illuminate\Support\Str;
use App\Data\Auth\SendOtpData;
use App\Data\Auth\RegisterData;
use App\Data\Auth\VerifyOtpData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Unauthenticated;
use App\Data\Response\Auth\RegisterResponseData;
use App\Data\Response\Auth\AuthTokenResponseData;
use App\Data\Response\Auth\OtpMessageResponseData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Authentication', 'OTP-based phone number authentication. Register with your phone number, verify the OTP to receive a bearer token.')]
class OTPAuthController extends Controller
{
    #[BodyParam('phone_number', 'string', 'The user\'s phone number.', required: true, example: '+8801712345678')]
    #[BodyParam('name', 'string', 'Optional display name for the new user.', required: false, example: 'John Doe')]
    #[Endpoint('Register', 'Register a new user and send an OTP to the provided phone number.')]
    #[Response(['message' => 'OTP sent to your phone number.'], 200, 'OTP sent successfully.')]
    #[Unauthenticated]
    /** @return RegisterResponseData */
    public function register(RegisterData $data): JsonResponse|Responsable
    {
        $user = User::create([
            'name' => $data->name ?? $data->phoneNumber,
            'phone_number' => $data->phoneNumber,
        ]);

        $otp = Str::otp();
        $user->cacheOtpFor10Minutes($otp);
        $user->sendOtpNotification($otp);

        return response()->json(RegisterResponseData::from([
            'message' => 'OTP sent to your phone.',
            'user' => UserData::fromModel($user),
            'otp' => app()->isProduction() ? null : $otp,
        ])->toArray(), HttpResponse::HTTP_CREATED);
    }

    #[BodyParam('phone_number', 'string', 'The user\'s phone number.', required: true, example: '+8801712345678')]
    #[BodyParam('otp', 'string', 'The OTP received on the phone number.', required: true, example: '123456')]
    #[Endpoint('Verify OTP', 'Verify the OTP sent to the phone number and receive an authentication token.')]
    #[Response(['message' => 'Phone number verified.', 'data' => ['id' => 1, 'name' => 'John Doe', 'phone_number' => '+8801712345678'], 'token' => '1|abc123...'], 200, 'OTP verified, token issued.')]
    #[Unauthenticated]
    /** @return AuthTokenResponseData|OtpMessageResponseData */
    public function verifyOtp(VerifyOtpData $data): JsonResponse|Responsable
    {
        $user = User::where('phone_number', $data->phoneNumber)->firstOrFail();

        $cachedOtp = $user->getCachedOtp();

        if (! $cachedOtp) {
            return response()->json(OtpMessageResponseData::from([
                'message' => 'OTP has expired.',
                'otp' => null,
            ])->toArray(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ((string) $cachedOtp !== $data->otp) {
            return response()->json(OtpMessageResponseData::from([
                'message' => 'Invalid OTP.',
                'otp' => null,
            ])->toArray(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->forgetCachedOtp();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(AuthTokenResponseData::from([
            'message' => 'OTP verified successfully.',
            'token' => $token,
            'user' => UserData::fromModel($user),
        ])->toArray());
    }

    #[BodyParam('phone_number', 'string', 'The user\'s phone number.', required: true, example: '+8801712345678')]
    #[Endpoint('Resend OTP', 'Resend the OTP to the specified phone number.')]
    #[Response(['message' => 'OTP resent.'], 200, 'OTP resent successfully.')]
    #[Unauthenticated]
    /** @return OtpMessageResponseData */
    public function resendOtp(SendOtpData $data): JsonResponse|Responsable
    {
        $user = User::where('phone_number', $data->phoneNumber)->firstOrFail();

        $otp = Str::otp();
        $user->cacheOtpFor10Minutes($otp);
        $user->sendOtpNotification($otp);

        return response()->json(OtpMessageResponseData::from([
            'message' => 'OTP resent successfully.',
            'otp' => app()->isProduction() ? null : $otp,
        ])->toArray());
    }

    #[BodyParam('phone_number', 'string', 'The user\'s phone number.', required: true, example: '+8801712345678')]
    #[Endpoint('Login', 'Send an OTP to the phone number to initiate login.')]
    #[Response(['message' => 'OTP sent to your phone number.'], 200, 'OTP sent successfully.')]
    #[Unauthenticated]
    /** @return OtpMessageResponseData */
    public function login(SendOtpData $data): JsonResponse|Responsable
    {
        $user = User::where('phone_number', $data->phoneNumber)->firstOrFail();

        $otp = Str::otp();
        $user->cacheOtpFor10Minutes($otp);
        $user->sendOtpNotification($otp);

        return response()->json(OtpMessageResponseData::from([
            'message' => 'Login OTP sent.',
            'otp' => app()->isProduction() ? null : $otp,
        ])->toArray());
    }
}
