<?php

namespace ZnKaz\Egov\Qr\Services;

use Illuminate\Support\Collection;
use ZnCore\Base\Encoders\AggregateEncoder;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use ZnKaz\Egov\Qr\Libs\DataSize;
use ZnKaz\Egov\Qr\Libs\WrapperDetector;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use DateTime;
use Exception;

class EncoderService
{

    private $wrapperDetector;
    private $defaultEntityWrapper;
    private $wrappers = [];
    private $resultEncoder;
    private $wrapperEncoder;
    private $dataSize;

    public function __construct(
        WrapperDetector $wrapperDetector,
        AggregateEncoder $resultEncoder,
        AggregateEncoder $wrapperEncoder,
        WrapperInterface $defaultEntityWrapper,
        DataSize $dataSize
    )
    {
        $this->wrapperDetector = $wrapperDetector;
        $this->resultEncoder = $resultEncoder;
        $this->wrapperEncoder = $wrapperEncoder;
        $this->entityWrapper = $defaultEntityWrapper;
        $this->dataSize = $dataSize;
    }

    public function encode(string $data): Collection
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Empty data for encode!');
        }
        $encoded = $this->resultEncoder->encode($data);
        $dataSize = $this->dataSize->getSize($this->wrapperEncoder, $this->entityWrapper);
        $encodedParts = str_split($encoded, $dataSize);
        $collection = new Collection();
        foreach ($encodedParts as $index => $item) {
            $encodedItem = $this->wrapperEncoder->encode($item);
            $barCodeEntity = new BarCodeEntity();
            $barCodeEntity->setId($index + 1);
            $barCodeEntity->setData($encodedItem);
            $barCodeEntity->setCount(count($encodedParts));
            $barCodeEntity->setCreatedAt(new DateTime());
            $barCodeEntity->setEntityEncoders($this->entityWrapper->getEncoders());
            $collection->add($this->entityWrapper->encode($barCodeEntity));
        }
        return $collection;
    }

    public function decode(Collection $encodedData)
    {
        $barCodeCollection = $this->arrayToCollection($encodedData);
        $resultCollection = new Collection();
        foreach ($barCodeCollection as $barCodeEntity) {
            $decodedItem = $this->wrapperEncoder->decode($barCodeEntity->getData());
            $resultCollection->add($decodedItem);
        }
        $resultArray = $resultCollection->toArray();
        return $this->resultEncoder->decode(implode('', $resultArray));
    }

    /**
     * @param Collection $array
     * @return Collection | BarCodeEntity[]
     * @throws Exception
     */
    private function arrayToCollection(Collection $array): Collection
    {
        $collection = new Collection();
        foreach ($array as $item) {
            $wrapper = $this->wrapperDetector->detect($item);
            $barCodeEntity = $wrapper->decode($item);
            $collection->add($barCodeEntity);
        }
        $arr = EntityHelper::indexingCollection($collection, 'id');
        ksort($arr);
        return new Collection($arr);
    }
}