<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use BackedEnum;
use App\Models\User;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;

class ReverbConsole extends Page
{
    protected string $view = 'filament.pages.reverb-console';

    protected static null|BackedEnum|string $navigationIcon = Heroicon::OutlinedSignal;

    protected static null|string|\UnitEnum $navigationGroup = 'Developer';

    protected static ?int $navigationSort = 99;

    protected static ?string $title = 'Reverb Debug Console';

    public string $userQuery = '';

    public ?int $selectedUserId = null;

    #[Computed]
    /** @return Collection<int, User> */
    public function matchingUsers(): Collection
    {
        if (2 > strlen($this->userQuery)) {
            return new Collection;
        }

        return User::query()
            ->where(function ($q): void {
                $q->where('name', 'like', "%{$this->userQuery}%")
                    ->orWhere('email', 'like', "%{$this->userQuery}%")
                    ->orWhere('phone_number', 'like', "%{$this->userQuery}%");
            })
            ->limit(8)
            ->get(['id', 'name', 'email', 'phone_number']);
    }

    /**
     * Sign a Pusher-compatible channel auth token server-side, bypassing the
     * broadcasting auth HTTP route entirely (avoids CSRF / middleware issues
     * in the Filament admin context).
     *
     * @return array{auth: string, channel_data?: string}
     */
    public function signChannelAuth(string $channel, string $socketId): array
    {
        $appKey = (string) config('broadcasting.connections.reverb.key', '');
        $appSecret = (string) config('broadcasting.connections.reverb.secret', '');

        $channelData = null;

        if (str_starts_with($channel, 'presence-')) {
            $user = $this->selectedUserId ? User::query()->find($this->selectedUserId) : null;

            $channelData = json_encode([
                'user_id' => (string) ($user?->getKey() ?? 0),
                'user_info' => ['name' => $user?->name ?? 'Admin'],
            ]);
        }

        $stringToSign = null !== $channelData
            ? "{$socketId}:{$channel}:{$channelData}"
            : "{$socketId}:{$channel}";

        $signature = hash_hmac('sha256', $stringToSign, $appSecret);

        $result = ['auth' => "{$appKey}:{$signature}"];

        if (null !== $channelData) {
            $result['channel_data'] = $channelData;
        }

        return $result;
    }

    /** @return array<string, mixed> */
    public function getViewData(): array
    {
        return [
            'reverbHost' => config('broadcasting.connections.reverb.public.host', 'localhost'),
            'reverbPort' => (int) config('broadcasting.connections.reverb.public.port', 8080),
            'reverbScheme' => config('broadcasting.connections.reverb.public.scheme', 'http'),
            'appKey' => config('broadcasting.connections.reverb.key', ''),
        ];
    }
}
