<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Serializer;

use App\Domain\Exceptions\InvalidDataException;
use App\Domain\Serializer\SerializerEnum;
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
