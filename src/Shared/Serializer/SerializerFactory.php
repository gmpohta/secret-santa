<?php

declare(strict_types=1);

namespace App\Shared\Serializer;

use App\Infrastructure\Serializer\JsonSerializer;
use App\Shared\Exceptions\InvalidDataException;

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
