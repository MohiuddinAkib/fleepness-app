<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BroadcastEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PresenceChannel;
use Database\Factories\LivestreamLikeFactory;
use App\Support\Broadcasting\BroadcastChannels;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Data\Broadcast\LivestreamLikeCountBroadcastData;
use Illuminate\Database\Eloquent\BroadcastsEventsAfterCommit;

class LivestreamLike extends Model
{
    /** @use HasFactory<LivestreamLikeFactory> */
    use BroadcastsEventsAfterCommit, HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'livestream_id',
        'user_id',
    ];

    /** @return BelongsTo<Livestream, $this> */
    public function livestream(): BelongsTo
    {
        return $this->belongsTo(Livestream::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return list<Channel> */
    public function broadcastOn(string $event): array
    {
        if ('created' !== $event && 'deleted' !== $event) {
            return [];
        }

        $this->loadMissing('livestream');

        if ($this->livestream->status->isFinished()) {
            return [];
        }

        return [
            new PresenceChannel(BroadcastChannels::livestreamPresence($this->livestream)),
        ];
    }

    public function broadcastAs(string $event): string
    {
        return match ($event) {
            'created', 'deleted' => BroadcastEvent::LivestreamLikeCountUpdated->value,
            default => "livestream_like_{$event}",
        };
    }

    /** @return array<string, mixed> */
    public function broadcastWith(string $event): array
    {
        $this->loadMissing('livestream');

        return new LivestreamLikeCountBroadcastData(
            likesCount: $this->livestream->likes()->count(),
        )->toArray();
    }
}
