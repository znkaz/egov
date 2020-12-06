<?php

namespace ZnKaz\Egov\Facades;

use Illuminate\Support\Collection;
use ZnKaz\Egov\Qr\Factories\EncoderServiceFactory;
use ZnKaz\Egov\Qr\Services\QrService;

class QrFacade
{

    public static function generateQrCode(string $content, int $margin = 1, int $size = 500, int $maxQrSize = 1183, string $qrFormat = 'png'): Collection
    {
        $encoderService = EncoderServiceFactory::createServiceForEgov($maxQrSize);
        $encoded = $encoderService->encode($content);
        $qrService = new QrService($qrFormat, $margin, $size);
        return $qrService->encode($encoded);
    }
}
