<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\VendorProfile;

it('returns the canonical account summary on the me summaries endpoint', function (): void {
    $user = User::factory()->create(['name' => 'Vendor User']);
    VendorProfile::factory()->for($user)->create([
        'status' => VendorStatus::Approved,
    ]);
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/me/summaries')
        ->assertOk()
        ->assertJsonPath('data.user_id', $user->getKey())
        ->assertJsonPath('data.name', 'Vendor User')
        ->assertJsonPath('data.role', 'vendor')
        ->assertJsonPath('data.status', VendorStatus::Approved->value);
});
