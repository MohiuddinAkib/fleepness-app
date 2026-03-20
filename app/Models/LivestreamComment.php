<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BroadcastEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PresenceChannel;
use App\Support\Broadcasting\BroadcastChannels;
use Database\Factories\LivestreamCommentFactory;
use App\Data\Broadcast\LivestreamCommentAuthorData;
use App\Data\Broadcast\LivestreamCommentMessageData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Data\Broadcast\LivestreamCommentBroadcastData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BroadcastsEventsAfterCommit;

class LivestreamComment extends Model
{
    /** @use HasFactory<LivestreamCommentFactory> */
    use BroadcastsEventsAfterCommit, HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'livestream_id',
        'user_id',
        'comment',
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
            'created' => BroadcastEvent::LivestreamCommentCreated->value,
            'deleted' => BroadcastEvent::LivestreamCommentDeleted->value,
            default => "livestream_comment_{$event}",
        };
    }

    /** @return array<string, mixed> */
    public function broadcastWith(string $event): array
    {
        if ('deleted' === $event) {
            return ['id' => $this->getKey()];
        }

        $this->loadMissing(['livestream', 'user']);

        return new LivestreamCommentBroadcastData(
            commenter: LivestreamCommentAuthorData::fromModel($this->user),
            comment: LivestreamCommentMessageData::fromModel($this),
        )->toArray();
    }
}
