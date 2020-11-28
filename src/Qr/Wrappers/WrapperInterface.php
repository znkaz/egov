<?php

namespace ZnKaz\Egov\Qr\Wrappers;

use ZnKaz\Egov\Qr\Entities\BarCodeEntity;

interface WrapperInterface
{

    public function getBlockSize(): int;

    public function setBlockSize(int $size);

    public function getEncoders(): array;

    public function setEncoders(array $encoders): void;

    public function isMatch(string $encodedData): bool;

    public function encode(BarCodeEntity $entity): string;

    public function decode(string $encodedData): BarCodeEntity;
}
