<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Uuid;

use App\Domain\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class UuidTest extends TestCase
{
    /**
     * @return \Generator<string, array{string}>
     */
    public static function invalidUuids(): \Generator
    {
        yield 'empty string' => [''];

        yield 'not valid UUID' => ['no-valid-uuid'];
    }

    /**
     * @return \Generator<string, array{string}>
     */
    public static function validUuids(): iterable
    {
        yield 'v1' => ['018f1fa9-c69b-73a3-910a-02bd06da9835'];

        yield 'v2' => ['000001f5-5e9a-21ea-9e00-0242ac130003'];

        yield 'v3' => ['53564aa3-4154-3ca5-ac90-dba59dc7d3cb'];

        yield 'v4' => ['1ee9aa1b-6510-4105-92b9-7171bb2f3089'];

        yield 'v5' => ['a35477ae-bfb1-5f2e-b5a4-4711594d855f'];

        yield 'v6' => ['1ea60f56-b67b-61fc-829a-0242ac130003'];

        yield 'v7' => ['01833ce0-3486-7bfd-84a1-ad157cf64005'];

        yield 'v8' => ['00112233-4455-8677-8899-aabbccddeeff'];
    }

    #[DataProvider('validUuids')]
    public function testFromStringDoesNotThrowOnValidUuids(string $uuidAsString): void
    {
        $uuid = Uuid::fromString($uuidAsString);

        self::assertSame($uuidAsString, $uuid->toString());
        self::assertSame($uuidAsString, (string) $uuid);
    }

    #[DataProvider('invalidUuids')]
    public function testFromStringThrowsOnInvalidString(string $string): void
    {
        self::expectExceptionObject(new \InvalidArgumentException("Expected valid UUID, got \"{$string}\"."));

        Uuid::fromString($string);
    }

    public function testV7GeneratesValidUuidVersion7(): void
    {
        $uuid = Uuid::v7();

        self::assertInstanceOf(UuidV7::class, RamseyUuid::getFactory()->fromString($uuid->toString()));
    }

    public function testV7GeneratesUuidWithCorrectDate(): void
    {
        $time = new \DateTimeImmutable('2023-11-02T17:05:00.871', timezone: new \DateTimeZone('UTC'));

        $uuid = Uuid::v7($time);

        $ramseyUuid = RamseyUuid::getFactory()->fromString($uuid->toString());
        self::assertInstanceOf(UuidV7::class, $ramseyUuid);
        self::assertEquals($time, $ramseyUuid->getDateTime());
    }

    public function testEquals(): void
    {
        $uuid1 = Uuid::v7();
        $uuid2 = Uuid::fromString($uuid1->toString());
        $uuid3 = Uuid::v7();

        self::assertTrue($uuid1->equals($uuid1));
        self::assertTrue($uuid1->equals($uuid2));
        self::assertFalse($uuid1->equals($uuid3));
    }

    public function testNil(): void
    {
        $uuidNil = Uuid::nil();

        self::assertEquals('00000000-0000-0000-0000-000000000000', $uuidNil->toString());
    }

    public function testMax(): void
    {
        $uuidNil = Uuid::max();

        self::assertEquals('ffffffff-ffff-ffff-ffff-ffffffffffff', $uuidNil->toString());
    }

    public function testJsonSerialize(): void
    {
        $uuid = Uuid::v7();

        self::assertSame($uuid->toString(), $uuid->jsonSerialize());
    }

    public function testValid(): void
    {
        $uuid = Uuid::v7();

        self::assertTrue(Uuid::isValid($uuid->toString()));
    }
}
