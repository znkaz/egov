<?php

namespace ZnKaz\Egov\Qr\Encoders;

use Exception;
use Symfony\Component\Uid\Uuid;
use ZipArchive;
use ZnCore\FileSystem\Helpers\FileHelper;

class ZipEncoder implements EncoderInterface
{

    public function encode($data)
    {
        $tmpDir = self::getTmpDirectory();
        $oneFile = $tmpDir . '/one';
        $zipFile = $tmpDir . '/arch.zip';

        $zip = new ZipArchive();
        $res = $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($res === TRUE) {
            file_put_contents($oneFile, $data);
            $zip->addFile($oneFile, 'one');
//            $xmlContent = $zip->addFromString('one', $data);
            $zip->close();
        } else {
            throw new Exception('Zip not opened!');
        }
        $binaryContent = file_get_contents($zipFile);
        FileHelper::removeDirectory($tmpDir);
        return $binaryContent;
    }

    public function decode($encodedData)
    {
        $tmpDir = self::getTmpDirectory();
        $oneFile = $tmpDir . '/one';
        $zipFile = $tmpDir . '/arch.zip';

        file_put_contents($zipFile, $encodedData);
        $zip = new ZipArchive();
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $xmlContent = $zip->getFromName('one');
            $zip->close();
        } else {
            throw new Exception('Zip not opened!');
        }
        FileHelper::removeDirectory($tmpDir);
        return $xmlContent;
    }

    private static function getTmpDirectory(): string
    {
        $tmpDir = __DIR__ . '/../../../../../../var/tmp/qrZip/' . Uuid::v4()->toRfc4122();
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