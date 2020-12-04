<?php


namespace ZnKaz\Egov\Qr\Encoders;


interface EncoderInterface
{

    public function compressionRate(): float;
    public function encode($data);
    public function decode($encodedData);

}
