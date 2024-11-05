<?php

declare(strict_types=1);

namespace App\Domain\Enums;

use App\Domain\Exceptions\InvalidDataException;

trait EnumTrait
{
    /**
     * @throws InvalidDataException
     */
    public static function fromName(?string $name): self
    {
        foreach (self::cases() as $case) {
            if ($name === $case->name) {
                return $case;
            }
        }

        throw new InvalidDataException("Invalid enum name {$name}");
    }

    public static function tryFromName(?string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($name === $case->name) {
                return $case;
            }
        }

        return null;
    }
}
