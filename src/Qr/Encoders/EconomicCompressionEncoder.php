<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;

class EconomicCompressionEncoder implements EncoderInterface
{

    private $encoders;

    public function __construct(array $encoders)
    {
        $this->encoders = $encoders;
    }

    public function encode($data)
    {
        foreach ($this->encoders as $encoder) {
            $encoderInstance = new $encoder;
            $encodedData[] = $encoderInstance->encode($data);
        }
        usort($encodedData, [ArrayHelper::class, 'sortByLen']);
        $encodedData = array_reverse($encodedData);
        return $encodedData[0];
    }

    public function decode($encodedData)
    {
        foreach ($this->encoders as $encoder) {
            $encoderInstance = new $encoder;
            $data = $encoderInstance->encode($encodedData);
            if(!empty($data) && $data != $encodedData) {
                return $data;
            }
        }
    }
}