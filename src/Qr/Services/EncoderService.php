<?php

namespace ZnKaz\Egov\Qr\Services;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Collection;
use ZnCore\Base\Helpers\StringHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCrypt\Base\Domain\Libs\Encoders\Base64Encoder;
use ZnCrypt\Base\Domain\Libs\Encoders\CollectionEncoder;
use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;
use ZnCrypt\Pki\X509\Domain\Helpers\QrDecoderHelper;
use ZnLib\Egov\Helpers\XmlHelper;
use ZnKaz\Egov\Qr\Encoders\ImplodeEncoder;
use ZnKaz\Egov\Qr\Encoders\SplitEncoder;
use ZnKaz\Egov\Qr\Encoders\XmlEncoder;
use ZnKaz\Egov\Qr\Entities\BarCodeEntity;
use ZnKaz\Egov\Qr\Libs\ClassEncoder;
use ZnKaz\Egov\Qr\Encoders\ZipEncoder;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use ZnKaz\Egov\Qr\Wrappers\XmlWrapper;
use Zxing\QrReader;

class EncoderService
{

    private $classEncoder;
    private $defaultEntityWrapper;
    private $wrappers = [
        JsonWrapper::class,
        XmlWrapper::class,
    ];
    private $entityEncoders = [
        'zip',
    ];

    public function __construct(WrapperInterface $defaultEntityWrapper)
    {
        $classEncoder = new ClassEncoder([
            'zip' => ZipEncoder::class,
            'base64' => Base64Encoder::class,
        ]);
        $this->classEncoder = $classEncoder;
        $this->entityWrapper = $defaultEntityWrapper;
    }

    public function encode($data, WrapperInterface $entityWrapper = null): Collection
    {
        $entityWrapper = $entityWrapper ?: $this->entityWrapper;
        $barCoreEntity1 = new BarCodeEntity();
        $resultEncoder = $this->classEncoder->encodersToClasses($this->entityEncoders);
        $encoded = $resultEncoder->encode($data);
        $encodedParts = str_split($encoded, $entityWrapper->getBlockSize());
        $collection = new Collection();
        foreach ($encodedParts as $index => $item) {
            $entityEncoder = $this->classEncoder->encodersToClasses($barCoreEntity1->getEntityEncoders());
            $encodedItem = $entityEncoder->encode($item);
            $barCodeEntity = new BarCodeEntity();
            $barCodeEntity->setId($index + 1);
            $barCodeEntity->setData($encodedItem);
            $barCodeEntity->setCount(count($encodedParts));
            $barCodeEntity->setCreatedAt('2020-11-17T20:55:33.671+06:00');
            $collection->add($entityWrapper->encode($barCodeEntity));
        }
        return $collection;
    }

    public function decode(Collection $encodedData)
    {
        $barCodeCollection = $this->arrayToCollection($encodedData);
        $resultCollection = new Collection();
        foreach ($barCodeCollection as $barCodeEntity) {
            $entityEncoders = $this->classEncoder->encodersToClasses($barCodeEntity->getEntityEncoders());
            $decodedItem = $entityEncoders->decode($barCodeEntity->getData());
            $resultCollection->add($decodedItem);
        }
        /** @var BarCodeEntity $firstBarCodeEntity */
        $firstBarCodeEntity = $barCodeCollection->first();
        $collectionEncoders = $firstBarCodeEntity->getCollectionEncoders();
        $resultEncoder = $this->classEncoder->encodersToClasses($collectionEncoders);
        return $resultEncoder->decode($resultCollection->toArray());

        //return $this->decodeBarCodeCollection($resultCollection, $barCodeCollection);
    }

    private function decodeBarCodeCollection(Collection $resultCollection, Collection $barCodeCollection)
    {

    }

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
            $wrapperInstance = new $wrapperClass;
            $isDetected = $wrapperInstance->isMatch($encoded);
            if($isDetected) {
                return $wrapperInstance;
            }
        }
        throw new \Exception('Wrapper not detected!');
    }

    private function decodeCollection($arr)
    {

    }
}