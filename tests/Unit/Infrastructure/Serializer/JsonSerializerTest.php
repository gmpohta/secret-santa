<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Serializer;

use App\Infrastructure\Serializer\JsonSerializer;
use PHPUnit\Framework\TestCase;

final class JsonSerializerTest extends TestCase
{
    private JsonSerializer $jsonSerializer;

    protected function setUp(): void
    {
        $this->jsonSerializer = new JsonSerializer();
    }

    public function testSerialize(): void
    {
        $data = new TestClassForSerialization('John', 30);

        $expectedJson = '{"name":"John","age":30}';
        $actualJson = $this->jsonSerializer->serialize($data);

        self::assertEquals($expectedJson, $actualJson);
    }

    public function testDeserialize(): void
    {
        $json = '{"name":"Smith","age":111}';
        $expectedObject = new TestClassForSerialization('Smith', 111);

        $actualObject = $this->jsonSerializer->deserialize($json, TestClassForSerialization::class);

        self::assertEquals($expectedObject, $actualObject);
    }

    public function testDenormalize(): void
    {
        $data = ['name' => 'Alice', 'age' => 66];
        $expectedObject = new TestClassForSerialization('Alice', 66);

        $actualObject = $this->jsonSerializer->denormalize($data, TestClassForSerialization::class);

        self::assertEquals($expectedObject, $actualObject);
    }

    public function testNormalize(): void
    {
        $object = new TestClassForSerialization('Foo', 99);

        $expectedArray = ['name' => 'Foo', 'age' => 99];
        $actualArray = $this->jsonSerializer->normalize($object);

        self::assertEquals($expectedArray, $actualArray);
    }

    public function testEncode(): void
    {
        $data = ['status' => 'success', 'code' => 200];
        $expectedJson = '{"status":"success","code":200}';
        $actualJson = $this->jsonSerializer->encode($data);

        self::assertEquals($expectedJson, $actualJson);
    }

    public function testEncode2(): void
    {
        $data = [NAN];
        $expectedJson = '';
        $actualJson = $this->jsonSerializer->encode($data, flags: 0);

        self::assertEquals($expectedJson, $actualJson);
    }

    public function testDecode(): void
    {
        $json = '{"result":"OK","message":"Data received"}';
        $expectedArray = ['result' => 'OK', 'message' => 'Data received'];

        $actualArray = $this->jsonSerializer->decode($json);

        self::assertEquals($expectedArray, $actualArray);
    }
}
