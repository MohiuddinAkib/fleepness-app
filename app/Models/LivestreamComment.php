<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PresenceChannel;
use Database\Factories\LivestreamCommentFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        if ('created' !== $event) {
            return [];
        }

        $this->loadMissing(['livestream', 'user']);

        if ($this->livestream->status->isFinished()) {
            return [];
        }

        return [
            new PresenceChannel($this->livestream->room_name),
        ];
    }

    public function broadcastAs(string $event): ?string
    {
        return match ($event) {
            'created' => 'livestream_comment_created',
            default => null,
        };
    }

    /** @return array<string, mixed> */
    public function broadcastWith(string $event): array
    {
        $this->loadMissing(['livestream', 'user']);

        return [
            'commenter' => [
                'id' => $this->user->getKey(),
                'name' => $this->user->name,
                'email' => $this->user->email,
                'avatar' => $this->user->getFirstMediaUrl('cover_image') ?: null,
                'phone_number' => $this->user->phone_number,
            ],
            'comment' => [
                'id' => $this->getKey(),
                'title' => $this->comment,
            ],
        ];
    }
}
