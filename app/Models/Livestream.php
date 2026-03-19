<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LivestreamStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\LivestreamFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Facades\Livestream as LivestreamFacade;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livestream extends Model implements HasMedia
{
    /** @use HasFactory<LivestreamFactory> */
    use HasFactory, InteractsWithMedia;

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

    public function getRoomName(): string
    {
        return sprintf('livestream_%s', $this->getKey());
    }

    public function getRecordingOutputPath(): string
    {
        return sprintf('livestreams/%s/%s', $this->getRoomName(), now()->timestamp);
    }

    public function startRecording(): void
    {
        $egress = LivestreamFacade::startRecording($this->getRoomName(), $this->getRecordingOutputPath());

        $this->update(['egress_id' => $egress->getEgressId()]);
    }

    public function stopRecording(): void
    {
        if (null === $this->egress_id) {
            return;
        }

        LivestreamFacade::stopRecording($this->egress_id);
    }
}
