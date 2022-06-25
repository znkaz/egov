<?php

namespace ZnKaz\Egov\Wrappers;


use ZnLib\Components\Format\Encoders\XmlEncoder;
use ZnLib\QrBox\Entities\BarCodeEntity;
use DateTime;
use ZnLib\QrBox\Wrappers\WrapperInterface;

class XmlWrapper extends \ZnLib\QrBox\Wrappers\XmlWrapper implements WrapperInterface
{

    protected static $favorId;

    public function encode(BarCodeEntity $entity): string
    {
        $barCode = [
            "@xmlns" => "http://barcodes.pdf.shep.nitec.kz/",
        ];
        $barCode['creationDate'] = $entity->getCreatedAt()->format(DateTime::RFC3339_EXTENDED);
        $barCode['elementData'] = $entity->getData();
        $barCode['elementNumber'] = $entity->getId();
        $barCode['elementsAmount'] = $entity->getCount();
        $barCode['FavorID'] = self::getFavorId();
        $xmlEncoder = new XmlEncoder();
        $encoded = $xmlEncoder->encode(['BarcodeElement' => $barCode]);
        $encoded = $this->cleanXml($encoded);
        return $encoded;
    }

    public function decode(string $encodedData): BarCodeEntity
    {
        $xmlEncoder = new XmlEncoder();
        $decoded = $xmlEncoder->decode($encodedData);
        $barCode = $decoded['BarcodeElement'];
        $entity = new BarCodeEntity();
        $entity->setId($barCode['elementNumber']);
        $entity->setCount($barCode['elementsAmount']);
        $entity->setData($barCode['elementData']);
        $entity->setCreatedAt(new DateTime($barCode['creationDate']));
        return $entity;
    }

    private static function getFavorId(): int {
        if( ! isset(self::$favorId)) {
            self::$favorId = self::getMicroTime();
        }
        return self::$favorId;
    }


    private static function getMicroTime(): int
    {
        $microtimeFloat = microtime(true) * 10000;
        $microtimeInt = (int)round($microtimeFloat);
        return $microtimeInt;
    }

}
