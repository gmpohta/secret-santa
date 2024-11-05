<?php

declare(strict_types=1);

namespace App\Shared\Serializer;

use App\Shared\Enums\EnumTrait;

enum SerializerEnum: string
{
    use EnumTrait;

    case JSON_SERIALIZER = 'json';
}
