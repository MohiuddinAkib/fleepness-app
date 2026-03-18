<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Email;

class UpdateEmailData extends Data
{
    public function __construct(
        public string $otp,
        #[Email]
        public string $email,
    ) {}
}
