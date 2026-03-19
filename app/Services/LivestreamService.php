<?php

declare(strict_types=1);

namespace App\Services;

use Livekit\FileInfo;
use Livekit\EgressInfo;
use Livekit\ImagesInfo;
use Livekit\ImageOutput;
use Livekit\SegmentsInfo;
use App\Models\Livestream;
use Livekit\EncodedFileType;
use Livekit\EncodingOptions;
use Livekit\EncodedFileOutput;
use Livekit\SegmentedFileOutput;
use Agence104\LiveKit\VideoGrant;
use Agence104\LiveKit\AccessToken;
use Illuminate\Support\Collection;
use Agence104\LiveKit\EncodedOutputs;
use Agence104\LiveKit\RoomCreateOptions;
use Agence104\LiveKit\AccessTokenOptions;
use Illuminate\Contracts\Filesystem\Cloud;
use App\Data\Dto\GeneratePublisherTokenData;
use Illuminate\Container\Attributes\Storage;
use App\Data\Dto\GenerateSubscriberTokenData;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Support\Livekit\Contracts\RoomServiceClient;
use App\Support\Livekit\Contracts\EgressServiceClient;

#[Singleton]
class LivestreamService
{
    public function __construct(
        protected readonly RoomServiceClient $roomService,
        protected readonly EgressServiceClient $egressService,
        #[Storage('r2')] protected readonly Cloud&Filesystem $r2fileSytem,
    ) {}

    public function generatePublisherToken(GeneratePublisherTokenData $data): string
    {
        $this->ensureRoomExists($data->roomName, $data->metadata);

        return $this->buildAccessTokenJwt(
            identity: $data->identity,
            displayName: $data->displayName,
            metadata: [],
            grant: $this->makePublisherGrant($data->roomName),
        );
    }

    public function generateSubscriberToken(GenerateSubscriberTokenData $data): string
    {
        return $this->buildAccessTokenJwt(
            identity: $data->identity,
            displayName: $data->displayName,
            metadata: $data->metadata,
            grant: $this->makeSubscriberGrant($data->roomName, $data->isPublic),
        );
    }

    public function startRecording(string $roomName, string $outputPath): EgressInfo
    {
        $fileOutput = resolve(EncodedFileOutput::class)
            ->setFileType(EncodedFileType::MP4)
            ->setFilepath($outputPath);
        $imageOutput = resolve(ImageOutput::class);
        $segmentedFileOutput = resolve(SegmentedFileOutput::class);
        // dd($imageOutput);

        $output = resolve(EncodedOutputs::class)
            ->setFile($fileOutput)
            ->setSegments($segmentedFileOutput)
            ->setImage($imageOutput);

        return $this->egressService->startRoomCompositeEgress(
            $roomName,
            'single-speaker',
            $output,
            new EncodingOptions()
                ->setWidth(1080)
                ->setHeight(1920),
            // ->setFramerate(30)
            // ->setAudioFrequency(3000),
            // ->setAudioCodec(AudioCodec::OPUS)
            // ->setVideoCodec(VideoCodec::H264_MAIN),
        );
    }

    public function stopRecording(string $egressId): EgressInfo
    {
        return $this->egressService->stopEgress($egressId);
    }

    /**
     * @return list<array{
     *     filename: string,
     *     startedAt: int,
     *     endedAt: int,
     *     duration: int,
     *     size: int,
     *     location: string
     * }>
     */
    public function getRecordingsFor(Livestream $livestream): array
    {
        return $this->egressInfoCollection($livestream)
            ->flatMap(
                fn (EgressInfo $egressInfo): array => collect($egressInfo->getFileResults())
                    ->map(fn (FileInfo $fileInfo): array => $this->formatRecording($fileInfo))
                    ->all()
            )
            ->values()
            ->all();
    }

    /**
     * @return list<array{
     *     filenamePrefix: string,
     *     imageCount: int,
     *     startedAt: int,
     *     endedAt: int
     * }>
     */
    public function getThumbnailsFor(Livestream $livestream): array
    {
        return $this->egressInfoCollection($livestream)
            ->flatMap(
                fn (EgressInfo $egressInfo): array => collect($egressInfo->getImageResults())
                    ->map(fn (ImagesInfo $info): array => $this->formatThumbnail($info))
                    ->all()
            )
            ->values()
            ->all();
    }

    /**
     * @return list<array{
     *     playlistName: string,
     *     livePlaylistName: string,
     *     duration: int,
     *     size: int,
     *     playlistLocation: string,
     *     livePlaylistLocation: string,
     *     segmentCount: int,
     *     startedAt: int,
     *     endedAt: int
     * }>
     */
    public function getShortVideosFor(Livestream $livestream): array
    {
        return $this->egressInfoCollection($livestream)
            ->flatMap(
                fn (EgressInfo $egressInfo): array => collect($egressInfo->getSegmentResults())
                    ->map(fn (SegmentsInfo $info): array => $this->formatShortVideo($info))
                    ->all()
            )
            ->values()
            ->all();
    }

    private function ensureRoomExists(string $roomName, array $metadata): void
    {
        $roomCreateOptions = tap(
            resolve(RoomCreateOptions::class),
            fn (RoomCreateOptions $options) => $options
                ->setName($roomName)
                ->setMetadata(json_encode($metadata))
        );

        $this->roomService->createRoom($roomCreateOptions);
    }

    private function buildAccessTokenJwt(
        string $identity,
        string $displayName,
        array $metadata,
        VideoGrant $grant,
    ): string {
        $tokenOptions = $this->makeAccessTokenOptions(
            identity: $identity,
            displayName: $displayName,
            metadata: $metadata,
        );

        return tap(
            resolve(AccessToken::class),
            fn (AccessToken $token) => $token
                ->init($tokenOptions)
                ->setGrant($grant)
        )->toJwt();
    }

    private function makeAccessTokenOptions(
        string $identity,
        string $displayName,
        array $metadata,
    ): AccessTokenOptions {
        return tap(
            resolve(AccessTokenOptions::class),
            fn (AccessTokenOptions $options) => $options
                ->setIdentity($identity)
                ->setName($displayName)
                ->setMetadata(json_encode($metadata))
        );
    }

    private function makePublisherGrant(string $roomName): VideoGrant
    {
        return tap(
            resolve(VideoGrant::class),
            fn (VideoGrant $grant) => $grant
                ->setRoomName($roomName)
                ->setRoomJoin()
                ->setRoomAdmin()
                ->setCanPublish()
                ->setCanPublishData()
        );
    }

    private function makeSubscriberGrant(string $roomName, bool $isPublic): VideoGrant
    {
        return tap(
            resolve(VideoGrant::class),
            fn (VideoGrant $grant) => $grant
                ->setRoomName($roomName)
                ->setRoomJoin()
                ->setCanPublish(false)
                ->setCanPublishData(! $isPublic)
        );
    }

    /** @return Collection<int, EgressInfo> */
    private function egressInfoCollection(Livestream $livestream): Collection
    {
        return collect(
            $this->egressService
                ->listEgress($livestream->room_name, $livestream->egress_id)
                ->getItems()
        );
    }

    /**
     * @return array{
     *     filename: string,
     *     startedAt: int,
     *     endedAt: int,
     *     duration: int,
     *     size: int,
     *     location: string
     * }
     */
    private function formatRecording(FileInfo $fileInfo): array
    {
        return [
            'filename' => $fileInfo->getFilename(),
            'startedAt' => $fileInfo->getStartedAt(),
            'endedAt' => $fileInfo->getEndedAt(),
            'duration' => $fileInfo->getDuration(),
            'size' => $fileInfo->getSize(),
            'location' => $fileInfo->getLocation(),
        ];
    }

    /**
     * @return array{
     *     filenamePrefix: string,
     *     imageCount: int,
     *     startedAt: int,
     *     endedAt: int
     * }
     */
    private function formatThumbnail(ImagesInfo $info): array
    {
        return [
            'filenamePrefix' => $info->getFilenamePrefix(),
            'imageCount' => $info->getImageCount(),
            'startedAt' => $info->getStartedAt(),
            'endedAt' => $info->getEndedAt(),
        ];
    }

    /**
     * @return array{
     *     playlistName: string,
     *     livePlaylistName: string,
     *     duration: int,
     *     size: int,
     *     playlistLocation: string,
     *     livePlaylistLocation: string,
     *     segmentCount: int,
     *     startedAt: int,
     *     endedAt: int
     * }
     */
    private function formatShortVideo(SegmentsInfo $info): array
    {
        return [
            'playlistName' => $info->getPlaylistName(),
            'livePlaylistName' => $info->getLivePlaylistName(),
            'duration' => $info->getDuration(),
            'size' => $info->getSize(),
            'playlistLocation' => $info->getPlaylistLocation(),
            'livePlaylistLocation' => $info->getLivePlaylistLocation(),
            'segmentCount' => $info->getSegmentCount(),
            'startedAt' => $info->getStartedAt(),
            'endedAt' => $info->getEndedAt(),
        ];
    }
}
