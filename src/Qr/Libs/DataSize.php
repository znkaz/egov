<?php

namespace ZnKaz\Egov\Qr\Libs;

use ZnCore\Base\Encoders\AggregateEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use DateTime;

class DataSize
{

    private $maxQrSize;
    private $encoder;
    private $wrapper;

    public function __construct(AggregateEncoder $encoder, WrapperInterface $wrapper, int $maxQrSize = 1183)
    {
        $this->encoder = $encoder;
        $this->wrapper = $wrapper;
        $this->maxQrSize = $maxQrSize;
    }

    public function getSize(AggregateEncoder $encoder, WrapperInterface $wrapper): int
    {
        $rate = $this->getDataSizeRateByEncoders();
        $dataSize = $this->getDataSizeByWrapper() / $rate;
        return $dataSize;
    }

    private function getDataSizeByWrapper()
    {
        $wrapSize = $this->getBarCodeSize();
        $dataSize = $this->maxQrSize - $wrapSize;
        return $dataSize;
    }

    private function getBarCodeSize(): int
    {
        $barCodeEntity = new BarCodeEntity();
        $barCodeEntity->setId(99);
        $barCodeEntity->setCount(99);
        $barCodeEntity->setCreatedAt(new DateTime('2020-11-17T20:55:33.671+06:00'));
        $barCodeEntity->setEntityEncoders($this->wrapper->getEncoders());
        $barCodeEntityClone = clone $barCodeEntity;
        $barCodeEntityClone->setData('');
        $block = $this->wrapper->encode($barCodeEntityClone);
        $len = mb_strlen($block);
        return $len;
    }

    private function getDataSizeRateByEncoders(): float
    {
        $encoders = $this->encoder->getEncoders();
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