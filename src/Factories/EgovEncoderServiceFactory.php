<?php

namespace ZnKaz\Egov\Factories;

use ZnKaz\Egov\Qr\Factories\ClassEncoderFactory;
use ZnKaz\Egov\Qr\Factories\EncoderServiceFactory;
use ZnKaz\Egov\Qr\Libs\ClassEncoder;
use ZnKaz\Egov\Qr\Libs\DataSize;
use ZnKaz\Egov\Qr\Libs\WrapperDetector;
use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use ZnKaz\Egov\Wrappers\XmlWrapper;

class EgovEncoderServiceFactory
{

    public static function createServiceForEgov(int $maxQrSize = 1183): EncoderService
    {
        $classEncoder = ClassEncoderFactory::create();
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);
        $wrappers = [
            XmlWrapper::class,
        ];
        $resultEncoders = ['zip'];
        return EncoderServiceFactory::createService($resultEncoders, $wrappers, $wrapper, $classEncoder, $maxQrSize);
    }
}