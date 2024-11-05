<?php

declare(strict_types=1);

namespace App\Domain\Serializer;

use App\Domain\Exceptions\InvalidDataException;
use App\Infrastructure\Serializer\JsonSerializer;

final class SerializerFactory
{
    public static function create(SerializerEnum $serializer): SerializerInterface
    {
        return match ($serializer) {
            SerializerEnum::JSON_SERIALIZER => new JsonSerializer(),
            default => throw new InvalidDataException('Unknown serializer'),
        };
    }
}
