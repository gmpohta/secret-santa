<?php

declare(strict_types=1);

namespace App\Api\ListUsers;

final class Request
{
    public function __construct(
        public int $page = 1,
        public int $limit = 100,
    ) {}
}
