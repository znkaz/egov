<?php

namespace ZnKaz\Egov\Tests\Unit;

use ZnCore\Base\Encoders\ZipEncoder;
use ZnKaz\Egov\Qr\Factories\ClassEncoderFactory;
use ZnKaz\Egov\Qr\Libs\DataSize;
use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use ZnKaz\Egov\Qr\Wrappers\XmlWrapper;

class QrBoxTest extends BaseTest
{

    public function testXmlBase64Zip()
    {
        $xmlFile = __DIR__ . '/../data/xml/example.xml';
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = $this->createEncoderService($wrapper, ['zip']);

        $data = file_get_contents($xmlFile);
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);
        $first = $encodedCollection->first();

        $this->assertEquals(5, $encodedCollection->count());
        $this->assertEquals($data, $decoded);
        $this->assertXmlString($first);
    }

    public function testJsonBase64Zip()
    {
        $xmlFile = __DIR__ . '/../data/xml/example.xml';
        $wrapper = new JsonWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = $this->createEncoderService($wrapper, ['zip']);
        $data = file_get_contents($xmlFile);
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);
        $first = $encodedCollection->first();

        $this->assertEquals(5, $encodedCollection->count());
        $this->assertEquals($data, $decoded);
        $this->assertJson($first);
    }

    public function testJsonDefault()
    {
        $wrapper = new JsonWrapper();
//        $wrapper->setEncoders();
        $encoderService = $this->createEncoderService($wrapper, []);
        $data = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);
        $first = $encodedCollection->first();

        $this->assertEquals(1, $encodedCollection->count());
        $this->assertJson($first);
        $result = json_decode($first, JSON_OBJECT_AS_ARRAY);
        $this->assertDateTimeString($result['createdAt']);
        $this->assertArraySubset([
            "id" => 1,
            "count" => 1,
            "data" => "qwertyuiopasdfghjklzxcvbnm1234567890",
//            "enc" => "base64",
            //"creationDate" => "2020-11-17T20:55:33.671+06:00"
        ], $result);
    }

    public function testJsonBase64()
    {
        $wrapper = new JsonWrapper();
        $wrapper->setEncoders(['base64']);

        $encoderService = $this->createEncoderService($wrapper);

        $data = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);
        $first = $encodedCollection->first();

        $this->assertEquals(1, $encodedCollection->count());
        $this->assertJson($first);
        $result = json_decode($first, JSON_OBJECT_AS_ARRAY);
        $this->assertArraySubset([
            "id" => 1,
            "count" => 1,
            "data" => "cXdlcnR5dWlvcGFzZGZnaGprbHp4Y3Zibm0xMjM0NTY3ODkw",
            "enc" => "base64",
//            "creationDate" => "2020-11-17T20:55:33.671+06:00"
        ], $result);
        $this->assertDateTimeString($result['createdAt']);
    }

    public function testJsonHex()
    {
        $wrapper = new JsonWrapper();
        $wrapper->setEncoders(['hex']);
        $encoderService = $this->createEncoderService($wrapper, []);
        $data = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);
        $first = $encodedCollection->first();

        $this->assertEquals(1, $encodedCollection->count());
        $this->assertJson($first);
        $result = json_decode($first, JSON_OBJECT_AS_ARRAY);
        $this->assertArraySubset([
            "id" => 1,
            "count" => 1,
            "data" => "71776572747975696f706173646667686a6b6c7a786376626e6d31323334353637383930",
            "enc" => "hex",
//            "creationDate" => "2020-11-17T20:55:33.671+06:00"
        ], $result);
        $this->assertDateTimeString($result['createdAt']);
    }

    public function testJsonBase64AndZip()
    {
        $wrapper = new JsonWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = $this->createEncoderService($wrapper, ['zip']);
        $data = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);
        $first = $encodedCollection->first();

        $this->assertEquals(1, $encodedCollection->count());
        $this->assertJson($first);
        //$this->assertEquals(278, mb_strlen($first));
        $result = json_decode($first, JSON_OBJECT_AS_ARRAY);
        $this->assertArraySubset([
            "id" => 1,
            "count" => 1,
            "enc" => "base64",
//            "creationDate" => "2020-11-17T20:55:33.671+06:00"
        ], $result);
        $this->assertDateTimeString($result['createdAt']);
        $zipEncoder = new ZipEncoder();
        $this->assertEquals($data, $zipEncoder->decode(base64_decode($result['data'])));
    }

    public function testJsonBase64AndGZip()
    {
        $wrapper = new JsonWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = $this->createEncoderService($wrapper, ['gz']);
        $data = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);
        $first = $encodedCollection->first();

        $this->assertEquals(1, $encodedCollection->count());
        $this->assertJson($first);
//        $this->assertEquals(277, mb_strlen($first));
        $result = json_decode($first, JSON_OBJECT_AS_ARRAY);
        $this->assertArraySubset([
            "id" => 1,
            "count" => 1,
            "data" => 'H4sIAAAAAAACAyssTy0qqSzNzC9ILE5JS8/Iys6pqkguS8rLNTQyNjE1M7ewNAAAByoXGiQAAAA=',
            "enc" => "base64",
//            "creationDate" => "2020-11-17T20:55:33.671+06:00"
        ], $result);
        $this->assertDateTimeString($result['createdAt']);
    }

    protected function createEncoderService(WrapperInterface $wrapper, array $resultEncoders = []): EncoderService
    {
        $classEncoder = ClassEncoderFactory::create();
        $wrappers = [
            XmlWrapper::class,
            JsonWrapper::class,
        ];

        $resultEncoder = $classEncoder->encodersToClasses($resultEncoders);
        $wrapperEncoder = $classEncoder->encodersToClasses($wrapper->getEncoders());
        $dataSize = new DataSize();
        $encoderService = new EncoderService(
            $wrappers,
            $resultEncoder,
            $wrapperEncoder,
            $wrapper,
            $dataSize
        );
        return $encoderService;
    }
}
