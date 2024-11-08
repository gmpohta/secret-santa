<?php

declare(strict_types=1);

namespace App\Domain\Dto;

final class ResponseListDto
{
    public function __construct(
        public array $items,
        public array $pagination,
    ) {}
}
