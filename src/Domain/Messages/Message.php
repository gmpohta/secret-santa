<?php

declare(strict_types=1);

namespace App\Domain\Messages;

final class Message
{
    public function __construct(
        public string $id,
        public bool $isSuccess = true,
    ) {}
}
