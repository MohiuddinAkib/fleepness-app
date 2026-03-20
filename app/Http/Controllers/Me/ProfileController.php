<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\UserData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Data\Me\UpdateProfileData;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Response\Me\UserResponseData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Profile', 'Manage the authenticated user\'s own profile.')]
class ProfileController extends Controller
{
    #[Authenticated]
    #[Endpoint('Get Profile', 'Retrieve the authenticated user\'s profile.')]
    #[Response(['data' => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'phone_number' => '+8801712345678']], 200, 'Profile retrieved successfully.')]
    /** @return UserData */
    public function show(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        return UserData::fromModel($user);
    }

    #[Authenticated]
    #[BodyParam('name', 'string', 'The user\'s display name.', required: false, example: 'Jane Doe')]
    #[BodyParam('email', 'string', 'The user\'s email address.', required: false, example: 'jane@example.com')]
    #[Endpoint('Update Profile', 'Update the authenticated user\'s profile fields.')]
    #[Response(['data' => ['id' => 1, 'name' => 'Jane Doe', 'email' => 'jane@example.com']], 200, 'Profile updated successfully.')]
    /** @return UserResponseData */
    public function update(UpdateProfileData $data, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $updates = [];

        if (! $data->name instanceof Optional) {
            $updates['name'] = $data->name;
        }

        if (! $data->email instanceof Optional) {
            $updates['email'] = $data->email;
        }

        if (! $data->phoneNumber instanceof Optional) {
            $updates['phone_number'] = $data->phoneNumber;
        }

        if ([] !== $updates) {
            $user->update($updates);
        }

        if (! $data->bannerImage instanceof Optional) {
            $user
                ->addMedia($data->bannerImage)
                ->toMediaCollection('banner_image');
        }

        if (! $data->coverImage instanceof Optional) {
            if (null === $data->coverImage) {
                $user->clearMediaCollection('cover_image');
            } else {
                $user
                    ->addMedia($data->coverImage)
                    ->toMediaCollection('cover_image');
            }
        }

        return response()->json(UserResponseData::from([
            'message' => 'Profile updated.',
            'data' => UserData::fromModel($user->fresh()),
        ])->toArray());
    }
}
