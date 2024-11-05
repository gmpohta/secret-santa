<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Serializer;

use App\Shared\Exceptions\InvalidDataException;
use App\Shared\Serializer\SerializerEnum;
use PHPUnit\Framework\TestCase;

final class SerializerEnumTest extends TestCase
{
    public function testFromNameReturnsCorrectValueForValidName(): void
    {
        self::assertSame('json', SerializerEnum::fromName('JSON_SERIALIZER'));
    }

    public function testFromNameThrowsExceptionForUnknownName(): void
    {
        $this->expectException(InvalidDataException::class);
        SerializerEnum::fromName('unknown_serializer');
    }
}
