<?php

declare(strict_types=1);

namespace App\Domain\Messages;

final class FailedMessage
{
    public function __construct(
        public string $originalMessageId,
        public string $reason,
    ) {}
}
