<?php

namespace ZnKaz\Egov\Qr\Factories;

use ZnKaz\Egov\Qr\Libs\ClassEncoder;
use ZnKaz\Egov\Qr\Libs\DataSize;
use ZnKaz\Egov\Qr\Libs\WrapperDetector;
use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use ZnKaz\Egov\Wrappers\XmlWrapper;

class EncoderServiceFactory
{

    public static function createService(array $resultEncoders, array $wrappers, WrapperInterface $wrapper, ClassEncoder $classEncoder, int $maxQrSize = 1183): EncoderService
    {
        $wrapperDetector = new WrapperDetector($wrappers);
        $resultEncoder = $classEncoder->encodersToClasses($resultEncoders);
        $wrapperEncoder = $classEncoder->encodersToClasses($wrapper->getEncoders());
        $dataSize = new DataSize($wrapperEncoder, $wrapper, $maxQrSize);
        $encoderService = new EncoderService(
            $wrapperDetector,
            $resultEncoder,
            $wrapperEncoder,
            $wrapper,
            $dataSize
        );
        return $encoderService;
    }
}