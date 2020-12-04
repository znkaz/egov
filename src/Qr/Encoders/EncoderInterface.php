<?php


namespace ZnKaz\Egov\Qr\Encoders;


interface EncoderInterface extends \ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface
{

    public function compressionRate(): float;

}