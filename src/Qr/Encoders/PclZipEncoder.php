<?php

namespace ZnKaz\Egov\Qr\Encoders;

class PclZipEncoder implements EncoderInterface
{

    public function encode($data)
    {
        $zipFile = tempnam(sys_get_temp_dir(), 'qrZip');
        $archive = new \PclZip($zipFile);
        $list = $archive->create([
            [
                PCLZIP_ATT_FILE_NAME => 'one',
                PCLZIP_ATT_FILE_CONTENT => $data,
//                PCLZIP_OPT_NO_COMPRESSION
            ]
        ]);
        $xmlContent = file_get_contents($zipFile);
        unlink($zipFile);
        return $xmlContent;
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