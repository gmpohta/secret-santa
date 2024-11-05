<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Serializer;

final class TestClassForSerialization
{
    public function __construct(
        public string $name,
        public int $age,
    ) {}
}
