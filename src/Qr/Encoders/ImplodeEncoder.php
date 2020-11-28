<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;

class ImplodeEncoder implements EncoderInterface
{

    private $maxLenght;

    public function __construct(int $maxLenght = 650)
    {
        $this->maxLenght = $maxLenght;
    }

    public function encode($data)
    {
        return $data;
        return str_split($data, $this->maxLenght);
    }

    public function decode($encodedData)
    {
        return $encodedData;
        return implode('', $encodedData);
    }
}