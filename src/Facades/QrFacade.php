<?php

namespace ZnKaz\Egov\Facades;

use Illuminate\Support\Collection;
use ZnKaz\Egov\Factories\EgovEncoderServiceFactory;
use ZnLib\QrBox\Entities\FileEntity;
use ZnLib\QrBox\Services\QrService;

class QrFacade
{

    /**
     * @param string $content
     * @param int $margin
     * @param int $size
     * @param int $maxQrSize
     * @param string $qrFormat
     * @return Collection | FileEntity[]
     */
    public static function generateQrCode(string $content, int $margin = 1, int $size = 500, int $maxQrSize = 1183, string $qrFormat = 'png'): Collection
    {
        $encoderService = EgovEncoderServiceFactory::createService($maxQrSize);
        $encoded = $encoderService->encode($content);
        $qrService = new QrService($qrFormat, $margin, $size);
        return $qrService->encode($encoded);
    }
}
