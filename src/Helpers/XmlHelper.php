<?php

namespace ZnKaz\Egov\Helpers;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Serializer;
use ZnCore\Base\Helpers\StringHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

class XmlHelper
{

    public static function getXmlEncoder()
    {
        /*$xml = new XmlEncoder([XmlEncoder::ROOT_NODE_NAME => 'response']);
        $serializer = new Serializer([new CustomNormalizer()], ['xml' => new XmlEncoder()]);
        $xml->setSerializer($serializer);*/
        $xml = new XmlEncoder();
        $context = [
            'xml_format_output' => true,
//            'xml_root_node_name' => null,
            'xml_encoding' => 'UTF-8',
//            XmlEncoder::STANDALONE => 'xmlStandalone',
        ];
    }

    public static function normalizeFormat(string $data): string
    {
        $xmlArray = XmlHelper::decode($data);
        return XmlHelper::encode($xmlArray);
    }

    public static function encode($data, string $rootName = null)
    {
        if(empty($data)) {
            throw new \Exception('Empty data');
        }
        $xml = new XmlEncoder();
        $context = [
            'xml_format_output' => true,
//            'xml_root_node_name' => null,
            'xml_encoding' => 'UTF-8',
//            XmlEncoder::STANDALONE => 'xmlStandalone',
        ];
        $encoded = $xml->encode($data, 'xml', $context);
        $encoded = str_replace('<response>', '', $encoded);
        $encoded = str_replace('</response>', '', $encoded);
        if(empty($rootName) && count($data) > 1) {
            throw new \Exception('Empty root name and collection type array');
        }
        $rootName = key($data);
        if(empty($rootName)) {
            throw new \Exception('Empty root name');
        }
//        $encoded .= "</$rootName>";
        $encoded = str_replace("\n  ", "\n", $encoded);
        $encoded = StringHelper::removeDoubleSpace($encoded, '\n', "\n");
        return $encoded;
    }

    public static function decode(string $encoded, string $rootName = null)
    {
        $xml = new \Symfony\Component\Serializer\Encoder\XmlEncoder;
        $context = [
            'xml_format_output' => true,
//            'xml_root_node_name' => $rootName,
            'xml_encoding' => 'UTF-8',
        ];
        $decoded = $xml->decode($encoded, 'xml', $context);
        $rootName = self::getRootName($encoded);
        return [
            $rootName => $decoded,
        ];
    }

    public static function getRootName(string $encoded)
    {
        $xml = simplexml_load_string($encoded);
        $rootName = $xml->getName();
        return $rootName;
    }
}