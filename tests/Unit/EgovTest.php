<?php

namespace Tests\Unit;

use ZnKaz\Egov\Qr\Encoders\Base64Encoder;
use ZnKaz\Egov\Qr\Encoders\ImplodeEncoder;
use ZnKaz\Egov\Qr\Encoders\ZipEncoder;
use ZnKaz\Egov\Qr\Libs\ClassEncoder;
use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Qr\Wrappers\XmlWrapper;
use ZnTool\Test\Base\BaseTest;

class EgovTest extends BaseTest
{

    public function testXmlWrapper()
    {
        $xmlFile = __DIR__ . '/../data/xml/example.xml';
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = $this->createService($wrapper);
        $data = file_get_contents($xmlFile);
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);

        $this->assertEquals(6, $encodedCollection->count());
        $this->assertEquals($data, $decoded);
        $this->assertContains('<?xml', $encodedCollection->first());
    }

    public function testJsonWrapper()
    {
        $xmlFile = __DIR__ . '/../data/xml/example.xml';
        $wrapper = new JsonWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = $this->createService($wrapper);
        $data = file_get_contents($xmlFile);
        $encodedCollection = $encoderService->encode($data);
        $decoded = $encoderService->decode($encodedCollection);

        //dd($encodedCollection);

        $this->assertEquals(5, $encodedCollection->count());
        $this->assertEquals($data, $decoded);
        $this->assertContains('{"', $encodedCollection->first());
    }

    private function createService($wrapper): EncoderService
    {
        return new EncoderService($wrapper);
    }
}
