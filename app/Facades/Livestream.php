<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\LivestreamService;
use Illuminate\Support\Facades\Facade;
use App\Data\Dto\GeneratePublisherTokenData;
use App\Data\Dto\GenerateSubscriberTokenData;

/**
 * @method static string generatePublisherToken(GeneratePublisherTokenData $data)
 * @method static string generateSubscriberToken(GenerateSubscriberTokenData $data)
 * @method static \Livekit\EgressInfo startRecording(string $roomName, string $outputPath)
 * @method static \Livekit\EgressInfo stopRecording(string $egressId)
 *
 * @see LivestreamService
 *
 * @mixin LivestreamService
 */
class Livestream extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LivestreamService::class;
    }
}
