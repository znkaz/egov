<?php

namespace ZnKaz\Egov\Qr\Factories;

use ZnKaz\Egov\Qr\Libs\DataSize;
use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Wrappers\XmlWrapper;

class EncoderServiceFactory
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
        $resultEncoder = $classEncoder->encodersToClasses($resultEncoders);
        $wrapperEncoder = $classEncoder->encodersToClasses($wrapper->getEncoders());
        $dataSize = new DataSize($maxQrSize);
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