<?php

declare(strict_types=1);

namespace App\Api\AddUsers;

final class Request
{
    public function __construct(
        /** @var array<int, array<string,mixed>> */
        public array $users = [],
    ) {}
}
