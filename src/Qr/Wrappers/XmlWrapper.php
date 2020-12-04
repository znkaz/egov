<?php

namespace ZnKaz\Egov\Qr\Wrappers;

use ZnKaz\Egov\Qr\Encoders\XmlEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;

class XmlWrapper implements WrapperInterface
{

    private $encoders = [
        'base64'
    ];

    public function getEncoders(): array
    {
        return $this->encoders;
    }

    public function setEncoders(array $encoders): void
    {
        $this->encoders = $encoders;
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
        $encoded = $xmlEncoder->encode(['BarcodeElement' => $barCode]);
        $encoded = trim($encoded);
        $encoded = preg_replace('/(\>\s+\<)/i', '><', $encoded);
        return $encoded;
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
