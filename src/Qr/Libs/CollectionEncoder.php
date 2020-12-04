<?php

namespace ZnKaz\Egov\Qr\Libs;

use Illuminate\Support\Collection;
use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Base\Helpers\InstanceHelper;

class CollectionEncoder implements EncoderInterface
{

    private $encoderCollection;

    public function __construct(Collection $encoderCollection)
    {
        $this->encoderCollection = $encoderCollection;
    }

    public function getEncoders(): Collection
    {
        return $this->encoderCollection;
    }

    public function encode($data)
    {
        //$data = EntityHelper::toArray($data);
        $encoders = $this->encoderCollection->all();
        foreach ($encoders as $encoderClass) {
            /** @var EncoderInterface $encoderInstance */
            $encoderInstance = InstanceHelper::ensure($encoderClass);
            $data = $encoderInstance->encode($data);
        }
        return $data;
    }

    public function decode($data)
    {
        $encoders = $this->encoderCollection->all();
        $encoders = array_reverse($encoders);
        foreach ($encoders as $encoderClass) {
            /** @var EncoderInterface $encoderInstance */
            $encoderInstance = InstanceHelper::ensure($encoderClass);
            $data = $encoderInstance->decode($data);
        }
        return $data;
    }

}
