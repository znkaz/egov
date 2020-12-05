<?php

namespace ZnKaz\Egov\Qr\Wrappers;

use ZnLib\Egov\Helpers\XmlHelper;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;

class JsonWrapper implements WrapperInterface
{

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
        return preg_match('#\{"#i', $encodedData);
    }

    public function encode(BarCodeEntity $entity): string
    {
        $barCode = [];
        $barCode['id'] = $entity->getId();
        $barCode['count'] = $entity->getCount();
        $barCode['data'] = $entity->getData();
        if($entity->getEntityEncoders()) {
            $barCode['enc'] = implode(',', $entity->getEntityEncoders());
        }
        $barCode['creationDate'] = $entity->getCreatedAt();
        $jsonContent = json_encode($barCode);
        return $jsonContent;
    }

    public function decode(string $encodedData): BarCodeEntity
    {
        $decoded = json_decode($encodedData, JSON_OBJECT_AS_ARRAY);
        $entity = new BarCodeEntity();
        $entity->setId($decoded['id']);
        $entity->setCount($decoded['count']);
        $entity->setData($decoded['data']);
        if(isset($decoded['enc'])) {
            $entity->setEntityEncoders(explode(',', $decoded['enc']));
        } else {
            $entity->setEntityEncoders([]);
        }
        $entity->setCreatedAt($decoded['creationDate']);
        return $entity;
    }
}
