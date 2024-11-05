<?php

declare(strict_types=1);

namespace App\Domain\Serializer;

use App\Domain\Enums\EnumTrait;

enum SerializerEnum: string
{
    use EnumTrait;

    case JSON_SERIALIZER = 'json';
}
