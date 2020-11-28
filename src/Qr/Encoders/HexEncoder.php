<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;

class HexEncoder implements EncoderInterface
{

    public function encode($data)
    {
        return bin2hex($data);
    }

    public function decode($encodedData)
    {
        return hex2bin($encodedData);
    }
}