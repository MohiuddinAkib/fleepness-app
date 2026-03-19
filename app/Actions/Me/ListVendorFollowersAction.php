<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ListVendorFollowersAction
{
    /**
     * @return Collection<int, User>
     */
    public function execute(User $user): Collection
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        /** @var Collection<int, User> $users */
        $users = $vendorProfile->followers()
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->values();

        return $users;
    }
}
