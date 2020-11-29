<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;

class GZipEncoder implements EncoderInterface
{

    public function encode($data)
    {
        return gzencode($data);
    }

    public function decode($encodedData)
    {
        return gzdecode($encodedData);
    }
}