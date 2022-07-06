<?php

namespace ZnKaz\Egov\Qr\Libs;

use ZnCore\Instance\Helpers\InstanceHelper;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnKaz\Egov\Qr\Encoders\EncoderInterface;

class CollectionEncoder implements EncoderInterface
{

    private $encoderCollection;

    public function __construct(Enumerable $encoderCollection)
    {
        $this->encoderCollection = $encoderCollection;
    }

    public function getEncoders(): Enumerable
    {
        return $this->encoderCollection;
    }

    public function encode($data)
    {
        //$data = EntityHelper::toArray($data);
        $encoders = $this->encoderCollection->toArray();
        foreach ($encoders as $encoderClass) {
            /** @var EncoderInterface $encoderInstance */
            $encoderInstance = InstanceHelper::ensure($encoderClass);
            $data = $encoderInstance->encode($data);
        }
        return $data;
    }

    public function decode($data)
    {
        $encoders = $this->encoderCollection->toArray();
        $encoders = array_reverse($encoders);
        foreach ($encoders as $encoderClass) {
            /** @var EncoderInterface $encoderInstance */
            $encoderInstance = InstanceHelper::ensure($encoderClass);
            $data = $encoderInstance->decode($data);
        }
        return $data;
    }

}
