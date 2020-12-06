<?php

namespace ZnKaz\Egov\Qr\Factories;

use ZnCore\Base\Encoders\GZipEncoder;
use ZnCore\Base\Encoders\ZipEncoder;
use ZnKaz\Egov\Qr\Encoders\Base64Encoder;
use ZnKaz\Egov\Qr\Encoders\HexEncoder;
use ZnKaz\Egov\Qr\Libs\ClassEncoder;

class ClassEncoderFactory
{

    public static function create(): ClassEncoder
    {
        $encoders = [
            'zip' => ZipEncoder::class,
            'gz' => new GZipEncoder(ZLIB_ENCODING_GZIP, 9),
            'gzDeflate' => new GZipEncoder(ZLIB_ENCODING_RAW, 9),
            'base64' => Base64Encoder::class,
            'b64' => Base64Encoder::class,
            'hex' => HexEncoder::class,
        ];
        $classEncoder = new ClassEncoder($encoders);
        return $classEncoder;
    }
}