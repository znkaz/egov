<?php

namespace ZnKaz\Egov\Qr\Encoders;


use Symfony\Component\Serializer\Encoder\XmlEncoder as SymfonyXmlEncoder;
use Exception;
use InvalidArgumentException;
use DomainException;
use ZnCore\Text\Helpers\TextHelper;

class XmlEncoder implements EncoderInterface
{

    private $formatOutput;
    private $encoding;
    private $xml;

    public function __construct(bool $formatOutput = true, string $encoding = 'UTF-8')
    {
        $this->formatOutput = $formatOutput;
        $this->encoding = $encoding;
        $this->xml = new SymfonyXmlEncoder();
    }

    public function encode($data)
    {
        if (empty($data)) {
            throw new InvalidArgumentException('Empty data');
        }
        if (count($data) > 1) {
            throw new DomainException('Empty root name and collection type array');
        }
        $encoded = $this->xml->encode($data, 'xml', $this->getContext());
        $encoded = $this->fixEncode($encoded);
        return $encoded;
    }

    public function decode($encoded)
    {
        $decoded = $this->xml->decode($encoded, 'xml', $this->getContext());
        $rootName = $this->getRootName($encoded);
        return [
            $rootName => $decoded,
        ];
    }

    private function fixEncode(string $encoded): string
    {
        $encoded = str_replace('<response>', '', $encoded);
        $encoded = str_replace('</response>', '', $encoded);
        $encoded = str_replace("\n  ", "\n", $encoded);
        $encoded = TextHelper::removeDoubleSpace($encoded, '\n', "\n");
        return $encoded;
    }

    private function getContext()
    {
        return [
            SymfonyXmlEncoder::ENCODING => $this->encoding,
            SymfonyXmlEncoder::FORMAT_OUTPUT => $this->formatOutput,
//            XmlEncoder::STANDALONE => 'xmlStandalone',
        ];
    }

    private function getRootName(string $encoded)
    {
        $xml = simplexml_load_string($encoded);
        $rootName = $xml->getName();
        return $rootName;
    }
}