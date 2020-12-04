<?php

namespace ZnKaz\Egov\Qr\Encoders;

class HexEncoder implements EntityEncoderInterface
{

    public function compressionRate(): float
    {
        return 2;
    }

    public function encode($data)
    {
        return bin2hex($data);
    }

    public function decode($encodedData)
    {
        return hex2bin($encodedData);
    }
}