<?php

namespace ZnKaz\Egov\Qr\Wrappers;

use ZnKaz\Egov\Qr\Encoders\XmlEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;

class XmlWrapper implements WrapperInterface
{

    private $blockSize = 650;

    public function getBlockSize(): int
    {
        return $this->blockSize;
    }

    public function setBlockSize(int $size)
    {
        $this->blockSize = $size;
    }

    public function isMatch(string $encodedData): bool
    {
        return preg_match('#<\?xml#i', $encodedData);
    }

    public function encode(BarCodeEntity $entity): string
    {
        $barCode = [
            "@xmlns" => "http://barcodes.pdf.shep.nitec.kz/",
        ];
        $barCode['creationDate'] = $entity->getCreatedAt();
        $barCode['elementData'] = $entity->getData();
        $barCode['elementNumber'] = $entity->getId();
        $barCode['elementsAmount'] = $entity->getCount();
        $barCode['FavorID'] = 10100464053940;
        $xmlEncoder = new XmlEncoder();
        return $xmlEncoder->encode(['BarcodeElement' => $barCode]);
    }

    public function decode(string $encodedData): BarCodeEntity
    {
        $xmlEncoder = new XmlEncoder();
        $decoded = $xmlEncoder->decode($encodedData);
        $entity = new BarCodeEntity();
        $entity->setId($decoded['BarcodeElement']['elementNumber']);
        $entity->setCount($decoded['BarcodeElement']['elementsAmount']);
        $entity->setData($decoded['BarcodeElement']['elementData']);
        $entity->setCreatedAt($decoded['BarcodeElement']['creationDate']);
        return $entity;
    }
}
