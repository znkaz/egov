<?php

namespace ZnKaz\Egov\Qr\Services;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Collection;

class QrService
{

    /*private $classEncoder;
    private $entityWrapper;

    public function __construct(ClassEncoder $classEncoder, $entityWrapper)
    {
        $this->classEncoder = $classEncoder;
        $this->entityWrapper = $entityWrapper;
    }*/

    public function encode(Collection $encoded): Collection
    {
        $collection = new Collection();
        foreach ($encoded as $i => $data) {
            $renderer = new ImageRenderer(
                new RendererStyle(700),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $image = $writer->writeString($data);
            $collection->add($image);
        }
        return $collection;
    }

}