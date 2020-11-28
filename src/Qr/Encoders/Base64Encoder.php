<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;

class Base64Encoder implements EncoderInterface
{

    public function encode($data)
    {
        return base64_encode($data);
    }

    public function decode($encodedData)
    {
        return base64_decode($encodedData);
    }
}