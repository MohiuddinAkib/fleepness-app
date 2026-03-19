<?php

declare(strict_types=1);

namespace App\Webhooks\Livekit;

use App\Models\User;
use App\Models\Livestream;
use App\Enums\LivestreamStatus;
use Agence104\LiveKit\WebhookReceiver;
use Illuminate\Database\Eloquent\Casts\Json;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class LivekitProcessWebhookJob extends ProcessWebhookJob
{
    public function handle(): void
    {
        $webhookPayload = $this->webhookCall->payload;

        $receiver = new WebhookReceiver(config('services.livekit.api_key'), config('services.livekit.api_secret'));
        $event = $receiver->receive(data_get($webhookPayload, 'raw_data', ''), null, true);

        $eventName = $event->getEvent();

        match ($eventName) {
            'room_started' => $this->handleRoomStarted($event),
            'room_finished' => $this->handleRoomFinished($event),
            'participant_joined' => $this->handleParticipantJoined($event),
            'egress_started' => $this->handleEgressStarted($event),
            'egress_ended' => $this->handleEgressEnded($event),
            default => null,
        };
    }

    private function getLivestreamFromRoomMetadata(mixed $event): ?Livestream
    {
        if (! $event->hasRoom()) {
            return null;
        }

        $roomMetadata = Json::decode($event->getRoom()->getMetadata());
        $livestreamId = data_get($roomMetadata, 'livestream_identity');

        return $livestreamId ? Livestream::query()->find($livestreamId) : null;
    }

    private function handleRoomStarted(mixed $event): void
    {
        $livestream = $this->getLivestreamFromRoomMetadata($event);

        if ($livestream && ! $livestream->is_started) {
            $livestream->update([
                'status' => LivestreamStatus::Started,
                'started_at' => now(),
            ]);
        }
    }

    private function handleRoomFinished(mixed $event): void
    {
        $livestream = $this->getLivestreamFromRoomMetadata($event);

        if ($livestream) {
            $totalDuration = $livestream->started_at
                ? (int) now()->diffInSeconds($livestream->started_at)
                : null;

            $livestream->update([
                'status' => LivestreamStatus::Finished,
                'ended_at' => now(),
                'total_duration' => $totalDuration,
            ]);
        }
    }

    private function handleParticipantJoined(mixed $event): void
    {
        if (! $event->hasRoom() || ! $event->hasParticipant()) {
            return;
        }

        // Skip publishers (they can publish tracks)
        if ($event->getParticipant()->getPermission()->getCanPublish()) {
            return;
        }

        $livestream = $this->getLivestreamFromRoomMetadata($event);

        if (! $livestream) {
            return;
        }

        $participantUserId = $event->getParticipant()->getIdentity();
        $user = User::query()->find($participantUserId);

        if ($user) {
            // Authenticated viewer — track via cache to avoid double-counting
            $cacheKey = "livestream_viewer_{$livestream->getKey()}_{$user->getKey()}";
            if (! cache()->has($cacheKey)) {
                cache()->put($cacheKey, true, now()->addHours(24));
                $livestream->increment('viewer_count');
            }
        } else {
            // Guest viewer — track by identity string
            $cacheKey = "livestream_viewer_{$livestream->getKey()}_{$participantUserId}";
            if (! cache()->has($cacheKey)) {
                cache()->put($cacheKey, true, now()->addHours(24));
                $livestream->increment('viewer_count');
            }
        }
    }

    private function handleEgressStarted(mixed $event): void
    {
        if (! $event->hasEgressInfo() || ! $event->hasRoom()) {
            return;
        }

        $livestream = $this->getLivestreamFromRoomMetadata($event);
        $eventEgressId = $event->getEgressInfo()->getEgressId();

        if ($livestream && $livestream->egress_id !== $eventEgressId) {
            $livestream->update(['egress_id' => $eventEgressId]);
        }
    }

    private function handleEgressEnded(mixed $event): void
    {
        if (! $event->hasEgressInfo()) {
            return;
        }

        $eventEgressId = $event->getEgressInfo()->getEgressId();
        $livestream = Livestream::query()->firstWhere('egress_id', $eventEgressId);

        if (! $livestream) {
            return;
        }

        $recordings = \App\Facades\Livestream::getRecordingsFor($livestream);
        $thumbnails = \App\Facades\Livestream::getThumbnailsFor($livestream);
        $shortVideos = \App\Facades\Livestream::getShortVideosFor($livestream);

        $livestream->update([
            'egress_metadata' => [
                'recordings' => $recordings,
                'thumbnails' => $thumbnails,
                'short_videos' => $shortVideos,
            ],
        ]);
    }
}
