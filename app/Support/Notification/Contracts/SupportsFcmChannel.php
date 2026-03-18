<?php

declare(strict_types=1);

namespace App\Support\Notification\Contracts;

use Kreait\Firebase\Messaging\CloudMessage;

interface SupportsFcmChannel
{
    public function toFcm(object $notifiable): CloudMessage;
}
