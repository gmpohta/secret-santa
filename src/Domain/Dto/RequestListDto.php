<?php

declare(strict_types=1);

namespace App\Domain\Dto;

final class RequestListDto
{
    public function __construct(
        public int $page = 1,
        public int $limit = 100,
    ) {}
}
