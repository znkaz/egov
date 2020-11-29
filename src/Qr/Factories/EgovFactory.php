<?php

namespace ZnKaz\Egov\Qr\Factories;

use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\XmlWrapper;

class EgovFactory
{

    public static function create(): EncoderService
    {
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);
        $encoderService = new EncoderService($wrapper, ['zip']);
        return $encoderService;
    }
}