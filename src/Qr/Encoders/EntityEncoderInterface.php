<?php

namespace ZnKaz\Egov\Qr\Encoders;

interface EntityEncoderInterface extends EncoderInterface
{

    public function compressionRate(): float;

}
