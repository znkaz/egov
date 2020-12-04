<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCrypt\Base\Domain\Helpers\SafeBase64Helper;

class Base64Encoder implements EntityEncoderInterface
{

    public function compressionRate(): float
    {
        return 4 / 3;
    }

    public function encode($data)
    {
//        return SafeBase64Helper::encode($data);
//        return base64_decode(strtr($input, '-_', '+/'));
        return base64_encode($data);
    }

    public function decode($encodedData)
    {
        return base64_decode($encodedData);
    }
}