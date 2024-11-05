<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Serializer;

use App\Domain\Serializer\SerializerEnum;
use App\Domain\Serializer\SerializerFactory;
use App\Infrastructure\Serializer\JsonSerializer;
use PHPUnit\Framework\TestCase;

final class SerializerFactoryTest extends TestCase
{
    public function testCreateReturnsJsonSerializer(): void
    {
        $serializer = SerializerFactory::create(SerializerEnum::JSON_SERIALIZER);
        self::assertInstanceOf(JsonSerializer::class, $serializer);
    }
}
