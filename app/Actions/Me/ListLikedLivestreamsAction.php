<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;
use App\Models\Livestream;
use Illuminate\Pagination\LengthAwarePaginator;

class ListLikedLivestreamsAction
{
    /**
     * @return LengthAwarePaginator<int, Livestream>
     */
    public function execute(User $user): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, Livestream> $paginator */
        $paginator = Livestream::query()
            ->whereHas('likes', fn ($query) => $query->where('user_id', $user->getKey()))
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return $paginator;
    }
}
