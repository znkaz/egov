<?php

namespace ZnKaz\Egov\Qr\Services;

use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Collection;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnKaz\Egov\Qr\Entities\FileEntity;

class QrService
{

    private $imageBackEnd;

    public function __construct(ImageBackEndInterface $imageBackEnd)
    {
        $this->imageBackEnd = $imageBackEnd;
    }

    /**
     * @param Collection $encoded
     * @return Collection | FileEntity[]
     */
    public function encode(Collection $encoded): Collection
    {
        $collection = new Collection();
        foreach ($encoded as $i => $data) {
            $renderer = new ImageRenderer(
                new RendererStyle(700),
                $this->imageBackEnd
            );
            $writer = new Writer($renderer);
            $extension = $this->getFileExtensionByImage();
            $fileEntity = $this->forgeFileEntity($extension, $writer->writeString($data));
            $collection->add($fileEntity);
        }
        return $collection;
    }

    private function forgeFileEntity(string $extension, string $content): FileEntity
    {
        $fileEntity = new FileEntity();

        $fileEntity->setExtension($extension);
        $mimeType = FileHelper::getMimeTypeByFileExtension($extension);
        $fileEntity->setMimeType($mimeType);
        $fileEntity->setContent($content);
        return $fileEntity;
    }

    public function getFileExtensionByImage()
    {
        if ($this->imageBackEnd instanceof SvgImageBackEnd) {
            return 'svg';
        } elseif ($this->imageBackEnd instanceof EpsImageBackEnd) {
            return 'eps';
        } elseif ($this->imageBackEnd instanceof ImagickImageBackEnd) {
            return 'png';
        }
    }
}