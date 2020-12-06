<?php

namespace ZnKaz\Egov\Qr\Wrappers;

use ZnCore\Base\Helpers\StringHelper;
use ZnCore\Base\Encoders\XmlEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use DateTime;

class XmlWrapper implements WrapperInterface
{

    private $favorId;
    private $encoders = [];

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
            //"@xmlns" => "http://barcodes.pdf.shep.nitec.kz/",
        ];
        $barCode['creationDate'] = $entity->getCreatedAt()->format(DateTime::RFC3339_EXTENDED);
        $barCode['elementData'] = $entity->getData();
        $barCode['elementNumber'] = $entity->getId();
        $barCode['elementsAmount'] = $entity->getCount();
        $xmlEncoder = new XmlEncoder();
        $encoded = $xmlEncoder->encode(['BarCode' => $barCode]);
        $encoded = trim($encoded);
        $encoded = preg_replace('/(\>\s+\<)/i', '><', $encoded);
        return $encoded;
    }

    public function decode(string $encodedData): BarCodeEntity
    {
        $xmlEncoder = new XmlEncoder();
        $decoded = $xmlEncoder->decode($encodedData);
        $entity = new BarCodeEntity();
        $entity->setId($decoded['BarCode']['elementNumber']);
        $entity->setCount($decoded['BarCode']['elementsAmount']);
        $entity->setData($decoded['BarCode']['elementData']);
        $entity->setCreatedAt(new DateTime($decoded['BarCode']['creationDate']));
        return $entity;
    }
}
