<?php

namespace ZnKaz\Egov\Facades;

use ZnKaz\Egov\Qr\Factories\EncoderServiceFactory;
use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Services\QrService;
use ZnKaz\Egov\Qr\Wrappers\XmlWrapper;

class QrFacade
{

    public static function generateQrCode(string $content, int $margin = 1, int $size = 500, int $maxQrSize = 1183, string $qrFormat = 'png')
    {
        $wrapper = new XmlWrapper();
        $wrapper->setEncoders(['base64']);

        $encoderService = new EncoderService($wrapper, ['zip'], $maxQrSize);

//        return $encoderService;
//        $encoderService = EncoderServiceFactory::createServiceForEgov();
        $encoded = $encoderService->encode($content);
        //dd(mb_strlen($encoded->first()));
        $qrService = new QrService($qrFormat, $margin, $size);
        return $qrService->encode($encoded);
    }
}
