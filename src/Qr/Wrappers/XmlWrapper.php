<?php

namespace ZnKaz\Egov\Qr\Wrappers;

use ZnCore\Base\Helpers\StringHelper;
use ZnKaz\Egov\Qr\Encoders\XmlEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use DateTime;

class XmlWrapper implements WrapperInterface
{

    private $favorId;
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

        $barCode['creationDate'] = $entity->getCreatedAt()->format(DateTime::RFC3339_EXTENDED);
        $barCode['elementData'] = $entity->getData();
        $barCode['elementNumber'] = $entity->getId();
        $barCode['elementsAmount'] = $entity->getCount();
        $barCode['FavorID'] = $this->getFavorId();
        $xmlEncoder = new XmlEncoder();
        $encoded = $xmlEncoder->encode(['BarcodeElement' => $barCode]);
        $encoded = trim($encoded);
        $encoded = preg_replace('/(\>\s+\<)/i', '><', $encoded);
        return $encoded;
    }

    private function getFavorId(): int {
        if( ! isset($this->favorId)) {
            $this->favorId = StringHelper::getMicroTime();
        }
        return $this->favorId;
    }

    public function decode(string $encodedData): BarCodeEntity
    {
        $xmlEncoder = new XmlEncoder();
        $decoded = $xmlEncoder->decode($encodedData);
        $entity = new BarCodeEntity();
        $entity->setId($decoded['BarcodeElement']['elementNumber']);
        $entity->setCount($decoded['BarcodeElement']['elementsAmount']);
        $entity->setData($decoded['BarcodeElement']['elementData']);
        $entity->setCreatedAt(new DateTime($decoded['BarcodeElement']['creationDate']));
        return $entity;
    }
}
