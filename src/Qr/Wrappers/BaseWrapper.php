<?php

namespace ZnKaz\Egov\Qr\Wrappers;

abstract class BaseWrapper
{

    protected $encoders = [];

    public function getEncoders(): array
    {
        return $this->encoders;
    }

    public function setEncoders(array $encoders): void
    {
        $this->encoders = $encoders;
    }
}
