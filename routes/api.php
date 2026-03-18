<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OTPAuthController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\DeviceTokenController;

Route::prefix('auth')->group(function (): void {
    // OTP-based auth
    Route::post('register', [OTPAuthController::class, 'register']);
    Route::post('verify-otp', [OTPAuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [OTPAuthController::class, 'resendOtp']);
    Route::post('login', [OTPAuthController::class, 'login']);

    // Social auth
    Route::get('social/{provider}', [SocialAuthController::class, 'redirect']);
    Route::get('social/{provider}/callback', [SocialAuthController::class, 'callback']);

    // Authenticated auth actions
    Route::middleware(['auth:sanctum', 'bind.user'])->group(function (): void {
        Route::post('logout', [SessionController::class, 'destroy']);
        Route::post('device-tokens', [DeviceTokenController::class, 'store']);
        Route::delete('device-tokens/{deviceToken}', [DeviceTokenController::class, 'destroy']);
    });
});
