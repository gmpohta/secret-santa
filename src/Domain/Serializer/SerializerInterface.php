<?php

declare(strict_types=1);

namespace App\Domain\Serializer;

interface SerializerInterface
{
    public const string IGNORED_ATTRIBUTES = 'ignored_attributes';
    public const string SKIP_NULL_VALUES = 'skip_null_values';

    /**
     * @param array<string, mixed> $context
     */
    public function serialize(?object $data, array $context = []): string;

    /**
     * @template TReturn
     *
     * @param class-string<TReturn> $classType
     * @param array<string, mixed> $context
     * @return TReturn
     */
    public function deserialize(?string $data, string $classType, array $context = []): object;

    /**
     * @template TReturn
     *
     * @param array<mixed> $data
     * @param class-string<TReturn> $classType
     * @param array<string, mixed> $context
     * @return TReturn
     */
    public function denormalize(?array $data, string $classType, array $context = []): object;

    /**
     * @param array<string, mixed> $context
     * @return array<mixed>
     */
    public function normalize(?object $data, array $context = []): array;

    /**
     * @param array<mixed> $data
     * @param int<1, 2147483647> $depth
     */
    public function encode(?array $data, ?int $depth = null, ?int $flags = null): string;

    /**
     * @param int<1, 2147483647> $depth
     * @return array<mixed>
     */
    public function decode(string $data, ?int $depth = null, ?int $flags = null): array;
}
