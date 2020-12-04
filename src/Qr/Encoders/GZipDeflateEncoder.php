<?php

namespace ZnKaz\Egov\Qr\Encoders;

class GZipDeflateEncoder implements EncoderInterface
{

    private $encoding;
    private $level;

    public function __construct(int $encoding = ZLIB_ENCODING_GZIP, int $level = -1)
    {
        $this->encoding = $encoding;
        $this->level = $level;
    }

    public function encode($data)
    {
        if($this->encoding == ZLIB_ENCODING_GZIP) {
            return gzencode($data, $this->level);
        } elseif($this->encoding == ZLIB_ENCODING_DEFLATE) {
            return gzdeflate($data, $this->level);
        } elseif($this->encoding == ZLIB_ENCODING_RAW) {
            return gzcompress($data, $this->level);
        }
        //return gzcompress($data, $this->level, ZLIB_ENCODING_GZIP);
//        return gzencode($data);
    }

    public function decode($encodedData)
    {
        if($this->encoding == ZLIB_ENCODING_GZIP) {
            return gzdecode($encodedData);
        } elseif($this->encoding == ZLIB_ENCODING_DEFLATE) {
            return gzinflate($encodedData);
        } elseif($this->encoding == ZLIB_ENCODING_RAW) {
            return gzuncompress($encodedData);
        }
//        return gzuncompress($encodedData);

    }
}