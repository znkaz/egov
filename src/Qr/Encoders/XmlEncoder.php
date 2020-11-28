<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;
use ZnKaz\Egov\Helpers\XmlHelper;

class XmlEncoder implements EncoderInterface
{

    public function encode($data)
    {
        return XmlHelper::encode($data);
    }

    public function decode($encodedData)
    {
//        dd($encodedData);
        return XmlHelper::decode($encodedData);
    }
}