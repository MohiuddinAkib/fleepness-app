<?php

declare(strict_types=1);

namespace App\Support\Notification\Contracts;

interface SupportsSmsChannel
{
    public function toSms(object $notifiable): string;
}
