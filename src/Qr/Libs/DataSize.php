<?php

namespace ZnKaz\Egov\Qr\Libs;

use ZnCore\Base\Encoders\AggregateEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use DateTime;

class DataSize
{

    private $maxQrSize;

    public function __construct(int $maxQrSize = 1183)
    {
        $this->maxQrSize = $maxQrSize;
    }

    public function getSize(AggregateEncoder $encoder, WrapperInterface $wrapper): int
    {
        $rate = $this->getDataSizeRateByEncoders($encoder);
        $dataSize = $this->getDataSizeByWrapper($wrapper) / $rate;
        return $dataSize;
    }

    private function getDataSizeByWrapper(WrapperInterface $wrapper)
    {
        $wrapSize = $this->getBarCodeSize($wrapper);
        $dataSize = $this->maxQrSize - $wrapSize;
        return $dataSize;
    }

    private function getBarCodeSize(WrapperInterface $wrapper): int
    {
        $barCodeEntity = new BarCodeEntity();
        $barCodeEntity->setId(99);
        $barCodeEntity->setCount(99);
        $barCodeEntity->setCreatedAt(new DateTime('2020-11-17T20:55:33.671+06:00'));
        $barCodeEntity->setEntityEncoders($wrapper->getEncoders());
        $barCodeEntityClone = clone $barCodeEntity;
        $barCodeEntityClone->setData('');
        $block = $wrapper->encode($barCodeEntityClone);
        $len = mb_strlen($block);
        return $len;
    }

    private function getDataSizeRateByEncoders(AggregateEncoder $encoder): float
    {
        $encoders = $encoder->getEncoders();
        $rate = 1;
        foreach ($encoders as $resultEncoder) {
            $resultEncoderInstance = new $resultEncoder;
            if ($resultEncoderInstance->compressionRate() > $rate) {
                $rate = $resultEncoderInstance->compressionRate();
            }
        }
        return $rate;
    }
}