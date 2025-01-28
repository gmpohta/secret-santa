<?php

declare(strict_types=1);

namespace App\Domain\Messages;

final class Message2
{
    public function __construct(
        public string $id,
    ) {}
}
