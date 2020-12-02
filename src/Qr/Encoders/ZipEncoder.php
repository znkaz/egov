<?php

namespace ZnKaz\Egov\Qr\Encoders;

use ZnCore\Base\Helpers\StringHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnCrypt\Base\Domain\Libs\Encoders\EncoderInterface;
use Exception;
use ZipArchive;

class ZipEncoder implements EncoderInterface
{

    public function encode($data)
    {

        return file_get_contents('/home/vitaliy/common/var/www/orleu/crypt/var/tmp/qrZip/2f22aaec-2214-40cd-d46d-d3f508bd4746/one.zip');

        $tmpDir = self::getTmpDirectory();
        $oneFile = $tmpDir . '/one';
        $zipFile = $tmpDir . '/arch.zip';

        $zip = new ZipArchive();
        $res = $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($res === TRUE) {
            file_put_contents($oneFile, $data);
//            dd($oneFile);
            $zip->addFile($oneFile, 'one');
//            $xmlContent = $zip->addFromString('one', $data);
            $zip->close();
        } else {
            throw new Exception('Zip not opened!');
        }
        $xmlContent = file_get_contents($zipFile);
        FileHelper::removeDirectory($tmpDir);
//        dd($zipFile);
//        unlink($zipFile);
        return $xmlContent;
    }

    public function decode($encodedData)
    {
        $tmpDir = self::getTmpDirectory();
        $oneFile = $tmpDir . '/one';
        $zipFile = $tmpDir . '/arch.zip';

//        $zipFile = tempnam(sys_get_temp_dir(), 'qrZip');
        file_put_contents($zipFile, $encodedData);
        $zip = new ZipArchive();
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $xmlContent = $zip->getFromName('one');
            $zip->close();
        } else {
            throw new Exception('Zip not opened!');
        }
//        unlink($zipFile);
        FileHelper::removeDirectory($tmpDir);
        return $xmlContent;
    }

    private static function getTmpDirectory(): string
    {
        $tmpDir = __DIR__ . '/../../../../../../var/tmp/qrZip/' . StringHelper::genUuid();
        FileHelper::createDirectory($tmpDir);
        return realpath($tmpDir);
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
}