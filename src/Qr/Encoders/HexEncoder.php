<?php

namespace ZnKaz\Egov\Qr\Encoders;

class HexEncoder extends \ZnCore\Base\Encoders\HexEncoder implements EntityEncoderInterface
{

    public function compressionRate(): float
    {
        return 2;
    }
}