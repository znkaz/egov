<?php

namespace ZnKaz\Egov\Qr\Entities;

class BarCodeEntity
{

    private $id = 1;
    private $count = 1;
    private $data;
    private $maxLenght = 650;
    private $createdAt;
    /*private $collectionEncoders = [
        //'zip',
    ];*/
    private $entityEncoders = [
        'base64'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getMaxLenght(): int
    {
        return $this->maxLenght;
    }

    public function setMaxLenght(int $maxLenght): void
    {
        $this->maxLenght = $maxLenght;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /*public function getCollectionEncoders()
    {
        return $this->collectionEncoders;
    }

    public function setCollectionEncoders($collectionEncoders): void
    {
        $this->collectionEncoders = $collectionEncoders;
    }*/

    public function getEntityEncoders(): array
    {
        return $this->entityEncoders;
    }

    public function setEntityEncoders(array $entityEncoders): void
    {
        $this->entityEncoders = $entityEncoders;
    }
}