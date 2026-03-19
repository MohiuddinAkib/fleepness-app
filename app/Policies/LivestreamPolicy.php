<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Livestream;
use Illuminate\Auth\Access\Response;

class LivestreamPolicy
{
    public function create(User $user): bool
    {
        return null !== $user->vendorProfile;
    }

    public function update(User $user, Livestream $livestream): bool|Response
    {
        if (null === $user->vendorProfile) {
            return Response::denyAsNotFound();
        }

        if (! $livestream->vendorProfile()->is($user->vendorProfile)) {
            return Response::denyAsNotFound();
        }

        if ($livestream->status->isFinished() || null !== $livestream->ended_at) {
            return Response::denyAsNotFound();
        }

        return true;
    }

    public function getPublisherToken(User $user, Livestream $livestream): bool|Response
    {
        return $this->update($user, $livestream);
    }

    public function getSubscriberToken(?User $user, Livestream $livestream): bool|Response
    {
        if ($livestream->status->isFinished() || null !== $livestream->ended_at) {
            return Response::denyAsNotFound();
        }

        return true;
    }

    public function addProducts(User $user, Livestream $livestream): bool|Response
    {
        return $this->update($user, $livestream);
    }

    public function removeProducts(User $user, Livestream $livestream): bool|Response
    {
        return $this->update($user, $livestream);
    }
}
