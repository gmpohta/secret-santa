<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Shared\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

final readonly class JsonSerializer implements SerializerInterface
{
    private SymfonySerializer $serializer;

    public function __construct()
    {
        $this->serializer = new SymfonySerializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function serialize(?object $data, array $context = []): string
    {
        return $this->serializer->serialize($data, JsonEncoder::FORMAT, $context);
    }

    /**
     * @template TReturn
     *
     * @param class-string<TReturn> $classType
     * @param array<string, mixed> $context
     * @return TReturn
     */
    public function deserialize(?string $data, string $classType, array $context = []): object
    {
        /** @var TReturn */
        return $this->serializer->deserialize($data ?? '', $classType, JsonEncoder::FORMAT, $context);
    }

    /**
     * @template TReturn
     *
     * @param array<mixed> $data
     * @param class-string<TReturn> $classType
     * @param array<string, mixed> $context
     * @return TReturn
     */
    public function denormalize(?array $data, string $classType, array $context = []): object
    {
        /** @var TReturn */
        return $this->serializer->denormalize($data, $classType, null, $context);
    }

    public function normalize(?object $data, array $context = []): array
    {
        return (array) $this->serializer->normalize($data, 'array', $context);
    }

    /**
     * @param array<mixed> $data
     * @param int<1, 2147483647> $depth
     */
    public function encode(?array $data, ?int $depth = null, ?int $flags = null): string
    {
        $json = json_encode(
            value: $data,
            depth: $depth ?? 512,
            flags: $flags ?? JSON_THROW_ON_ERROR,
        );

        if (false === $json) {
            return '';
        }

        return $json;
    }

    /**
     * @param int<1, 2147483647> $depth
     * @return array<mixed>
     */
    public function decode(string $data, ?int $depth = null, ?int $flags = null): array
    {
        /** @var array<mixed> */
        return json_decode(
            json: $data,
            depth: $depth ?? 512,
            flags: $flags ?? JSON_THROW_ON_ERROR,
            associative: true,
        );
    }
}
