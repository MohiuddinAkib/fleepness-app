<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use App\Notifications\LoginOtpNotification;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Support\Notification\Contracts\SupportsFcmChannel;
use App\Support\Notification\Contracts\FcmNotifiableByDevice;
use App\Support\Notification\Contracts\FcmBroadcastNotifiableByDevice;

class User extends Authenticatable implements FcmBroadcastNotifiableByDevice, FcmNotifiableByDevice, FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Filament
    // -------------------------------------------------------------------------

    public function canAccessPanel(Panel $panel): bool
    {
        if ('vendor' === $panel->getId()) {
            return null !== $this->vendorProfile;
        }

        return (bool) $this->is_admin;
    }

    // -------------------------------------------------------------------------
    // OTP helpers
    // -------------------------------------------------------------------------

    public function otpCacheKey(): string
    {
        return "otp_{$this->phone_number}";
    }

    public function cacheOtpFor10Minutes(string $otp): bool
    {
        return cache()->put($this->otpCacheKey(), $otp, now()->addMinutes(10));
    }

    public function getCachedOtp(mixed $default = null): mixed
    {
        return cache()->get($this->otpCacheKey(), $default);
    }

    public function forgetCachedOtp(): bool
    {
        return cache()->forget($this->otpCacheKey());
    }

    public function sendOtpNotification(string $otp): void
    {
        $this->notify(new LoginOtpNotification($otp));
    }

    /** @param string|list<string> $token */
    public function removeDeviceToken(array|string $token): mixed
    {
        return $this->deviceTokens()->whereIn('token', (array) $token)->delete();
    }

    // -------------------------------------------------------------------------
    // Notification routing
    // -------------------------------------------------------------------------

    public function receivesBroadcastNotificationsOn(): string
    {
        return "user_{$this->getKey()}";
    }

    /** @return list<string> */
    public function routeBroadcastNotificationForFcmTokens(): array
    {
        return $this->deviceTokens->pluck('token')->all();
    }

    /** @return list<string> */
    public function routeNotificationForFcmTokens(Notification&SupportsFcmChannel $notification): array
    {
        return $this->deviceTokens->pluck('token')->all();
    }

    public function routeNotificationForSms(mixed $notification = null): ?string
    {
        return $this->phone_number;
    }

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    /** @return HasOne<VendorProfile, $this> */
    public function vendorProfile(): HasOne
    {
        return $this->hasOne(VendorProfile::class);
    }

    /** @return HasMany<Address, $this> */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /** @return HasOne<Address, $this> */
    public function defaultAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /** @return HasMany<DeviceToken, $this> */
    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    /** @return HasMany<UserPaymentAccount, $this> */
    public function paymentAccounts(): HasMany
    {
        return $this->hasMany(UserPaymentAccount::class);
    }

    /** @return HasMany<Transaction, $this> */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /** @return HasMany<Order, $this> */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** @return BelongsToMany<VendorProfile, $this> */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(VendorProfile::class, 'vendor_followers', 'user_id', 'vendor_profile_id')
            ->withTimestamps();
    }
}
