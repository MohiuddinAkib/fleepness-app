<?php

declare(strict_types=1);

namespace App\Support\Notification\Contracts;

interface SupportsFcmDeviceChannel extends SupportsFcmChannel
{
    /**
     * @return string|string[]|null
     */
    public function toFcmTokens(object $notifiable);
}
