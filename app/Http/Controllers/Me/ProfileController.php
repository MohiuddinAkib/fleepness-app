<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\UserData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Data\Me\UpdateProfileData;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Container\Attributes\CurrentUser;

class ProfileController extends Controller
{
    public function show(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        return UserData::fromModel($user);
    }

    public function update(UpdateProfileData $data, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $updates = [];

        if (! $data->name instanceof Optional) {
            $updates['name'] = $data->name;
        }

        if (! $data->email instanceof Optional) {
            $updates['email'] = $data->email;
        }

        if ([] !== $updates) {
            $user->update($updates);
        }

        return UserData::fromModel($user->fresh())->additional(['message' => 'Profile updated.']);
    }
}
