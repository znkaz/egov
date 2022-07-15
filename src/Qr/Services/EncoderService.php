<?php

namespace ZnKaz\Egov\Qr\Services;

use Exception;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\Collection\Libs\Collection;
use ZnCore\Collection\Helpers\CollectionHelper;
use ZnKaz\Egov\Qr\Encoders\Base64Encoder;
use ZnKaz\Egov\Qr\Encoders\EconomicCompressionEncoder;
use ZnKaz\Egov\Qr\Encoders\GZipEncoder;
use ZnKaz\Egov\Qr\Encoders\HexEncoder;
use ZnKaz\Egov\Qr\Encoders\ImplodeEncoder;
use ZnKaz\Egov\Qr\Encoders\SplitEncoder;
use ZnKaz\Egov\Qr\Encoders\ZipEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use ZnKaz\Egov\Qr\Libs\ClassEncoder;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use ZnKaz\Egov\Qr\Wrappers\XmlWrapper;
use ZnLib\Egov\Helpers\XmlHelper;
use Zxing\QrReader;

class EncoderService
{

    private $classEncoder;
    private $defaultEntityWrapper;
    private $wrappers = [
        JsonWrapper::class,
        XmlWrapper::class,
    ];
    private $resultEncoders = [];
    private $maxQrSize;

    public function __construct(WrapperInterface $defaultEntityWrapper, array $resultEncoders = [], int $maxQrSize = 1183)
    {
        // todo: сделать экономный выбор компрессии
        $classEncoder = new ClassEncoder([
//            'zip' => PclZipEncoder::class,
            'zip' => ZipEncoder::class,
            'gzip' => new GZipEncoder(ZLIB_ENCODING_GZIP, 9),
            'gzipDeflate' => new GZipEncoder(ZLIB_ENCODING_RAW, 9),
            'gz' => GZipEncoder::class,
            'base64' => Base64Encoder::class,
            'b64' => Base64Encoder::class,
            'hex' => HexEncoder::class,
        ]);
        $this->resultEncoders = $resultEncoders;
        $this->classEncoder = $classEncoder;
        $this->entityWrapper = $defaultEntityWrapper;
        $this->maxQrSize = $maxQrSize;
    }

    private function calcEncodedLen()
    {

    }

    private function getBarCodeSize(): int
    {
        $barCodeEntity = new BarCodeEntity();
        $barCodeEntity->setId(99);
        $barCodeEntity->setCount(99);
        $barCodeEntity->setCreatedAt('2020-11-17T20:55:33.671+06:00');
        $barCodeEntity->setEntityEncoders($this->entityWrapper->getEncoders());
        $barCodeEntityClone = clone $barCodeEntity;
        $barCodeEntityClone->setData('');
        $block = $this->entityWrapper->encode($barCodeEntityClone);
        $len = mb_strlen($block);
        return $len;
    }

    private function getDataSize()
    {
        $wrapSize = $this->getBarCodeSize();
        $dataSize = $this->maxQrSize - $wrapSize;
        return $dataSize;
    }

    private function getDataSizeRateByEncoders($encoders): float
    {
        $rate = 1;
        foreach ($encoders as $resultEncoder) {
            $resultEncoderInstance = new $resultEncoder;
            if ($resultEncoderInstance->compressionRate() > $rate) {
                $rate = $resultEncoderInstance->compressionRate();
            }
        }
        return $rate;
    }

    public function encode(string $data/*, WrapperInterface $entityWrapper = null*/): Enumerable
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Empty data for encode!');
        }
        $entityWrapper = /*$entityWrapper ?:*/
            $this->entityWrapper;
        $barCoreEntity1 = new BarCodeEntity();
        $resultEncoder = $this->classEncoder->encodersToClasses($this->resultEncoders);
        $encoded = $resultEncoder->encode($data);
        $entityEncoder = $this->classEncoder->encodersToClasses($entityWrapper->getEncoders());
        $rate = $this->getDataSizeRateByEncoders($entityEncoder->getEncoders());
        $dataSize = $this->getDataSize() / $rate;
        $encodedParts = str_split($encoded, $dataSize);
        $collection = new Collection();
        foreach ($encodedParts as $index => $item) {
            $entityEncoder = $this->classEncoder->encodersToClasses($entityWrapper->getEncoders());
            $encodedItem = $entityEncoder->encode($item);
            $barCodeEntity = new BarCodeEntity();
            $barCodeEntity->setId($index + 1);
            $barCodeEntity->setData($encodedItem);
            $barCodeEntity->setCount(count($encodedParts));
            $barCodeEntity->setCreatedAt('2020-11-17T20:55:33.671+06:00');
            $barCodeEntity->setEntityEncoders($entityWrapper->getEncoders());
            $collection->add($entityWrapper->encode($barCodeEntity));
        }
        return $collection;
    }

    public function decode(Enumerable $encodedData)
    {
        $barCodeCollection = $this->arrayToCollection($encodedData);
        $resultCollection = new Collection();
        foreach ($barCodeCollection as $barCodeEntity) {
            $entityEncoders = $this->classEncoder->encodersToClasses($barCodeEntity->getEntityEncoders());
            $decodedItem = $entityEncoders->decode($barCodeEntity->getData());
            $resultCollection->add($decodedItem);
        }
        $collectionEncoders = $this->resultEncoders;
        $resultEncoder = $this->classEncoder->encodersToClasses($collectionEncoders);
        $rr = $resultCollection->toArray();
        return $resultEncoder->decode(implode('', $rr));

        //return $this->decodeBarCodeCollection($resultCollection, $barCodeCollection);
    }

    private function decodeBarCodeCollection(Enumerable $resultCollection, Enumerable $barCodeCollection)
    {

    }

    /**
     * @param Enumerable $array
     * @return Enumerable | BarCodeEntity[]
     * @throws Exception
     */
    private function arrayToCollection(Enumerable $array): Enumerable
    {
        $collection = new Collection();
        foreach ($array as $item) {
            $wrapper = $this->detectWrapper($item);
            $barCodeEntity = $wrapper->decode($item);
            $collection->add($barCodeEntity);
        }
        $arr = CollectionHelper::indexing($collection, 'id');
        ksort($arr);
        return new Collection($arr);
    }

    private function detectWrapper(string $encoded): WrapperInterface
    {
        foreach ($this->wrappers as $wrapperClass) {
            /** @var WrapperInterface $wrapperInstance */
            $wrapperInstance = new $wrapperClass;
            $isDetected = $wrapperInstance->isMatch($encoded);
            if ($isDetected) {
                return $wrapperInstance;
            }
        }
        throw new \Exception('Wrapper not detected!');
    }

    private function decodeCollection($arr)
    {

    }
}