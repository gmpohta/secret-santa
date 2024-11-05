<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Serializer;

use App\Infrastructure\Serializer\JsonSerializer;
use App\Shared\Serializer\SerializerEnum;
use App\Shared\Serializer\SerializerFactory;
use PHPUnit\Framework\TestCase;

final class SerializerFactoryTest extends TestCase
{
    public function testCreateReturnsJsonSerializer(): void
    {
        $serializer = SerializerFactory::create(SerializerEnum::JSON_SERIALIZER);
        self::assertInstanceOf(JsonSerializer::class, $serializer);
    }
}
