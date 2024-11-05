<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Framework;

use App\Shared\Serializer\SerializerEnum;
use App\Shared\Serializer\SerializerFactory;
use App\Shared\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

final class AbstractControllerTest extends TestCase
{
    private TestClassController $testClass;

    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->testClass = new TestClassController();
        $this->serializer = SerializerFactory::create(SerializerEnum::JSON_SERIALIZER);
    }

    public function testSuccessResponse(): void
    {
        $data = ['key' => 'value'];
        $message = 'success';
        $code = 200;

        $response = $this->testClass->successResponse();

        $body = $this->serializer->decode((string) $response->getContent());

        self::assertTrue($body['success'] ?? null);
        self::assertSame($data, $body['data'] ?? null);
        self::assertSame($message, $body['message'] ?? null);
        self::assertSame($code, $response->getStatusCode());
    }

    public function testFailResponse(): void
    {
        $message = 'fail';
        $code = 400;

        $response = $this->testClass->failResponse();

        $body = $this->serializer->decode((string) $response->getContent());

        self::assertFalse($body['success'] ?? null);
        self::assertSame($message, $body['message'] ?? null);
        self::assertSame($code, $response->getStatusCode());
    }
}
