<?php

namespace ZnKaz\Egov\Qr\Wrappers;

use ZnLib\Egov\Helpers\XmlHelper;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use DateTime;

class JsonWrapper extends BaseWrapper implements WrapperInterface
{

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
        $barCode['createdAt'] = $entity->getCreatedAt()->format(DateTime::RFC3339_EXTENDED);
        $jsonContent = json_encode($barCode);
        return $jsonContent;
    }

    public function decode(string $encodedData): BarCodeEntity
    {
        $barCode = json_decode($encodedData, JSON_OBJECT_AS_ARRAY);
        $entity = new BarCodeEntity();
        $entity->setId($barCode['id']);
        $entity->setCount($barCode['count']);
        $entity->setData($barCode['data']);
        if(isset($barCode['enc'])) {
            $entity->setEntityEncoders(explode(',', $barCode['enc']));
        } else {
            $entity->setEntityEncoders([]);
        }
        $entity->setCreatedAt(new DateTime($barCode['createdAt']));
        return $entity;
    }
}
