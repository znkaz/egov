<?php

namespace ZnKaz\Egov\Qr\Services;

use Illuminate\Support\Collection;
use ZnCore\Base\Encoders\AggregateEncoder;
use ZnCore\Base\Helpers\InstanceHelper;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use ZnKaz\Egov\Qr\Libs\ClassEncoder;
use ZnKaz\Egov\Qr\Libs\DataSize;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use DateTime;
use Exception;

class EncoderService
{

    private $defaultEntityWrapper;
    private $wrappers = [];
    private $resultEncoder;
    private $wrapperEncoder;
    private $dataSize;

    public function __construct(
        array $wrappers,
        AggregateEncoder $resultEncoder,
        AggregateEncoder $wrapperEncoder,
        WrapperInterface $defaultEntityWrapper,
        DataSize $dataSize,
        int $maxQrSize = 1183
    )
    {
        $this->wrappers = $wrappers;
        $this->resultEncoder = $resultEncoder;
        $this->wrapperEncoder = $wrapperEncoder;
        $this->entityWrapper = $defaultEntityWrapper;
        $this->dataSize = $dataSize;
    }

    public function encode(string $data/*, WrapperInterface $entityWrapper = null*/): Collection
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Empty data for encode!');
        }
        $entityWrapper = /*$entityWrapper ?:*/
            $this->entityWrapper;
        $encoded = $this->resultEncoder->encode($data);

        $dataSize = $this->dataSize->getSize($this->wrapperEncoder, $entityWrapper);

        $encodedParts = str_split($encoded, $dataSize);
        $collection = new Collection();
        foreach ($encodedParts as $index => $item) {
            $encodedItem = $this->wrapperEncoder->encode($item);
            $barCodeEntity = new BarCodeEntity();
            $barCodeEntity->setId($index + 1);
            $barCodeEntity->setData($encodedItem);
            $barCodeEntity->setCount(count($encodedParts));
            $barCodeEntity->setCreatedAt(new DateTime());
            $barCodeEntity->setEntityEncoders($entityWrapper->getEncoders());
            $collection->add($entityWrapper->encode($barCodeEntity));
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
            $wrapper = $this->detectWrapper($item);
            $barCodeEntity = $wrapper->decode($item);
            $collection->add($barCodeEntity);
        }
        $arr = EntityHelper::indexingCollection($collection, 'id');
        ksort($arr);
        return new Collection($arr);
    }

    private function detectWrapper(string $encoded): WrapperInterface
    {
        foreach ($this->wrappers as $wrapperClass) {
            /** @var WrapperInterface $wrapperInstance */
            $wrapperInstance = InstanceHelper::create($wrapperClass);
            $isDetected = $wrapperInstance->isMatch($encoded);
            if ($isDetected) {
                return $wrapperInstance;
            }
        }
        throw new \Exception('Wrapper not detected!');
    }
}