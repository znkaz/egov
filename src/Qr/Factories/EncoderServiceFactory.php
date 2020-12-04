<?php

namespace ZnKaz\Egov\Qr\Factories;

use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\XmlWrapper;

class EncoderServiceFactory
{

    public static function createServiceForEgov(int $maxQrSize = 1183): EncoderService
    {
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = new EncoderService($wrapper, ['zip'], $maxQrSize);
        return $encoderService;
    }
}