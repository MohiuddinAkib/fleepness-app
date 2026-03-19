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
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OTPAuthController extends Controller
{
    public function register(RegisterData $data): JsonResponse|Responsable
    {
        $user = User::create([
            'name' => $data->name,
            'phone_number' => $data->phoneNumber,
        ]);

        $otp = Str::otp();
        $user->cacheOtpFor10Minutes($otp);
        $user->sendOtpNotification($otp);

        $payload = [
            'message' => 'OTP sent to your phone.',
            'user' => UserData::fromModel($user),
        ];

        if (! app()->isProduction()) {
            $payload['otp'] = $otp;
        }

        return response()->json($payload, HttpResponse::HTTP_CREATED);
    }

    public function verifyOtp(VerifyOtpData $data): JsonResponse|Responsable
    {
        $user = User::where('phone_number', $data->phoneNumber)->firstOrFail();

        $cachedOtp = $user->getCachedOtp();

        if (! $cachedOtp) {
            return response()->json(['message' => 'OTP has expired.'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ((string) $cachedOtp !== $data->otp) {
            return response()->json(['message' => 'Invalid OTP.'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->forgetCachedOtp();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully.',
            'token' => $token,
            'user' => UserData::fromModel($user),
        ]);
    }

    public function resendOtp(SendOtpData $data): JsonResponse|Responsable
    {
        $user = User::where('phone_number', $data->phoneNumber)->firstOrFail();

        $otp = Str::otp();
        $user->cacheOtpFor10Minutes($otp);
        $user->sendOtpNotification($otp);

        $payload = ['message' => 'OTP resent successfully.'];

        if (! app()->isProduction()) {
            $payload['otp'] = $otp;
        }

        return response()->json($payload);
    }

    public function login(SendOtpData $data): JsonResponse|Responsable
    {
        $user = User::where('phone_number', $data->phoneNumber)->firstOrFail();

        $otp = Str::otp();
        $user->cacheOtpFor10Minutes($otp);
        $user->sendOtpNotification($otp);

        $payload = ['message' => 'Login OTP sent.'];

        if (! app()->isProduction()) {
            $payload['otp'] = $otp;
        }

        return response()->json($payload);
    }
}
