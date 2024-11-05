<?php

declare(strict_types=1);

namespace App\Shared\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;
use function Ramsey\Uuid\v7;

/**
 * @psalm-immutable
 */
final readonly class Uuid
{
    /**
     * @param non-empty-string $uuid
     */
    public function __construct(
        public string $uuid,
    ) {}

    public static function nil(): self
    {
        return new self('00000000-0000-0000-0000-000000000000');
    }

    public static function max(): self
    {
        return new self('ffffffff-ffff-ffff-ffff-ffffffffffff');
    }

    /**
     * @psalm-pure
     * @psalm-assert-if-true =non-empty-string $string
     */
    public static function isValid(string $string): bool
    {
        return RamseyUuid::isValid($string);
    }

    /**
     * @psalm-pure
     */
    public static function fromString(string $string): self
    {
        if (self::isValid($string)) {
            return new self(strtolower($string));
        }

        throw new \InvalidArgumentException(\sprintf('Expected valid UUID, got "%s".', $string));
    }

    public static function v7(\DateTimeImmutable $time = new \DateTimeImmutable()): self
    {
        return new self(v7($time));
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->uuid;
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $uuid): bool
    {
        return $this->uuid === $uuid->uuid;
    }

    public function jsonSerialize(): string
    {
        return $this->uuid;
    }
}
