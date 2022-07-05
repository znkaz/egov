<?php

namespace ZnKaz\Egov\Qr\Libs;

use ZnCore\Domain\Collection\Interfaces\Enumerable;
use ZnCore\Domain\Collection\Libs\Collection;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnCore\Base\Instance\Helpers\InstanceHelper;
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
