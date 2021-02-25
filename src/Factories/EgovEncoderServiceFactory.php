<?php

namespace ZnKaz\Egov\Factories;

use ZnLib\QrBox\Factories\ClassEncoderFactory;
use ZnLib\QrBox\Factories\EncoderServiceFactory;
use ZnLib\QrBox\Services\EncoderService;
use ZnKaz\Egov\Wrappers\XmlWrapper;

class EgovEncoderServiceFactory
{

    public static function createService(int $maxQrSize = 1183): EncoderService
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