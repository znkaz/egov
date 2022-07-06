<?php

namespace ZnKaz\Egov\Qr\Services;

use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\PlainTextRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use ZnCore\Enum\Helpers\EnumHelper;
use ZnCore\FileSystem\Helpers\MimeTypeHelper;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\Collection\Libs\Collection;
use ZnKaz\Egov\Qr\Enums\ImageExtensionEnum;
use ZnLib\QrBox\Entities\FileEntity;

//use ZnKaz\Egov\Qr\Entities\FileEntity;

class QrService
{

    private $format;
    private $imageBackEnd;
    private $size;
    private $margin;
    private $render;

    public function __construct(string $format = 'png', int $margin = 1, int $size = 400, int $compressionQuality = 100)
    {
        EnumHelper::validate(ImageExtensionEnum::class, $format, null, "Image extension \"$format\" not supported!");
        $this->format = $format;
        $this->size = $size;
        $this->margin = $margin;
        if ($format == ImageExtensionEnum::SVG) {
            $this->imageBackEnd = new SvgImageBackEnd();
        } elseif ($format == ImageExtensionEnum::EPS) {
            $this->imageBackEnd = new EpsImageBackEnd();
        } elseif ($format == ImageExtensionEnum::TXT) {

        } else {
            $this->imageBackEnd = new ImagickImageBackEnd($format);
        }
        if ($format == ImageExtensionEnum::TXT) {
            $this->render = new PlainTextRenderer($margin);
        } else {
            $this->render = new ImageRenderer(
                new RendererStyle($size, $margin),
                $this->imageBackEnd
            );
        }
    }

    /**
     * @param Enumerable $encoded
     * @return Enumerable | FileEntity[]
     */
    public function encode(Enumerable $encoded): Enumerable
    {
        $collection = new Collection();
        $writer = new Writer($this->render);
        $extension = $this->format;
        foreach ($encoded as $i => $data) {
            $fileEntity = $this->forgeFileEntity($extension, $writer->writeString($data));
            $collection->add($fileEntity);
        }
        return $collection;
    }

    private function forgeFileEntity(string $extension, string $content): FileEntity
    {
        $fileEntity = new FileEntity();
        $fileEntity->setExtension($extension);
        $mimeType = MimeTypeHelper::getMimeTypeByExt($extension);
        $fileEntity->setMimeType($mimeType);
        $fileEntity->setContent($content);
        return $fileEntity;
    }
}