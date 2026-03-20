<?php

declare(strict_types=1);

namespace App\Models;

use App\Data\LivestreamData;
use App\Enums\BroadcastEvent;
use App\Enums\LivestreamStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Database\Factories\LivestreamFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Facades\Livestream as LivestreamFacade;
use App\Support\Broadcasting\BroadcastChannels;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\BroadcastsEventsAfterCommit;

class Livestream extends Model implements HasMedia
{
    /** @use HasFactory<LivestreamFactory> */
    use BroadcastsEventsAfterCommit, HasFactory, InteractsWithMedia, Notifiable;

    /** @var list<string> */
    protected $fillable = [
        'vendor_profile_id',
        'title',
        'description',
        'room_id',
        'egress_id',
        'egress_metadata',
        'status',
        'viewer_count',
        'scheduled_at',
        'started_at',
        'ended_at',
        'total_duration',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => LivestreamStatus::class,
            'egress_metadata' => 'json',
            'viewer_count' => 'integer',
            'total_duration' => 'integer',
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();
    }

    public function isStarted(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isStarted());
    }

    public function isScheduled(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isScheduled());
    }

    public function isFinished(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isFinished());
    }

    /** @return BelongsTo<VendorProfile, $this> */
    public function vendorProfile(): BelongsTo
    {
        return $this->belongsTo(VendorProfile::class);
    }

    /** @return HasMany<LivestreamComment, $this> */
    public function comments(): HasMany
    {
        return $this->hasMany(LivestreamComment::class);
    }

    /** @return HasMany<LivestreamLike, $this> */
    public function likes(): HasMany
    {
        return $this->hasMany(LivestreamLike::class);
    }

    /** @return HasMany<LivestreamSave, $this> */
    public function saves(): HasMany
    {
        return $this->hasMany(LivestreamSave::class);
    }

    /** @return BelongsToMany<Product, $this> */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'livestream_products');
    }

    public function roomName(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('livestream_%s', $this->getKey()));
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return $this->room_name;
    }

    public function recordingOutputPath(): Attribute
    {
        return Attribute::get(fn (): string => sprintf('livestreams/%s/%s', $this->room_name, now()->timestamp));
    }

    public function recordings(): Attribute
    {
        return Attribute::get(
            fn (): array => is_array($this->egress_metadata)
                ? (array) ($this->egress_metadata['recordings'] ?? [])
                : []
        );
    }

    public function thumbnails(): Attribute
    {
        return Attribute::get(
            fn (): array => is_array($this->egress_metadata)
                ? (array) ($this->egress_metadata['thumbnails'] ?? [])
                : []
        );
    }

    public function startRecording(): void
    {
        $egress = LivestreamFacade::startRecording($this->room_name, $this->recording_output_path);

        $this->update(['egress_id' => $egress->getEgressId()]);
    }

    public function stopRecording(): void
    {
        if (null === $this->egress_id) {
            return;
        }

        LivestreamFacade::stopRecording($this->egress_id);
    }

    /** @return list<Channel> */
    public function broadcastOn(string $event): array
    {
        return match ($event) {
            'created' => [new Channel(BroadcastChannels::LivestreamFeed)],
            'updated' => [new Channel(BroadcastChannels::LivestreamFeed), new Channel(BroadcastChannels::livestreamPresence($this))],
            default => [],
        };
    }

    public function broadcastAs(string $event): string
    {
        return match ($event) {
            'created' => BroadcastEvent::LivestreamCreated->value,
            'updated' => BroadcastEvent::LivestreamUpdated->value,
            default => "livestream_{$event}",
        };
    }

    /** @return array<string, mixed> */
    public function broadcastWith(string $event): array
    {
        return LivestreamData::fromModel(
            $this->loadMissing(['media', 'vendorProfile'])
        )->toArray();
    }
}
