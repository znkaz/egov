<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;
use Exception;
use ZipArchive;

class ZipEncoder implements EncoderInterface
{

    public function encode($data)
    {
        $zipFile = tempnam(sys_get_temp_dir(), 'qrZip');
        $zip = new \ZipArchive();
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $xmlContent = $zip->addFromString('one', $data);
            $zip->close();
        } else {
            throw new Exception('Zip not opened!');
        }
        $xmlContent = file_get_contents($zipFile);
        unlink($zipFile);
        return $xmlContent;
    }

    private function open(): ZipArchive
    {
        $zipFile = tempnam(sys_get_temp_dir(), 'qrZip');
        $zip = new ZipArchive();
        $res = $zip->open($zipFile);
        if ($res !== TRUE) {
            throw new Exception('Zip not opened!');
        }
        return $zip;
    }

    private function close(ZipArchive $zip)
    {
        $zip->close();
        unlink($zipFile);
    }

    public function decode($encodedData)
    {
        $zipFile = tempnam(sys_get_temp_dir(), 'qrZip');
        file_put_contents($zipFile, $encodedData);
        $zip = new \ZipArchive();
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $xmlContent = $zip->getFromName('one');
            $zip->close();
        } else {
            throw new Exception('Zip not opened!');
        }
        unlink($zipFile);
        return $xmlContent;
    }
}