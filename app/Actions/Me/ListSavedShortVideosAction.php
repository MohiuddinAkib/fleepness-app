<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;
use App\Models\ShortVideo;
use Illuminate\Pagination\LengthAwarePaginator;

class ListSavedShortVideosAction
{
    /**
     * @return LengthAwarePaginator<int, ShortVideo>
     */
    public function execute(User $user): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, ShortVideo> $paginator */
        $paginator = ShortVideo::query()
            ->whereHas('saves', fn ($query) => $query->where('user_id', $user->getKey()))
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return $paginator;
    }
}
