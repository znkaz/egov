<?php

namespace ZnKaz\Egov\Tests\Unit;

//use ZnTool\Test\Base\BaseTest;

abstract class BaseTest extends \ZnTool\Test\Base\BaseTest
{

    public function assertZipContent(string $actual)
    {
        $isZip = mb_substr($actual, 0, 10) === "PK\x03\x04\x14\x00\x02\x00\x08\x00";
        $this->assertTrue($isZip);
    }

    public function assertDateTimeString(string $actual)
    {
        $this->assertRegExp('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}\+\d{2}:\d{2}$/', $actual);
    }

    public function assertXmlString(string $actual)
    {
        $this->assertRegExp('/^<\?xml.+>[\s\S]+<\/.+>\s*$/', $actual);
    }

    public function assertNotXmlString(string $actual)
    {
        $this->assertNotRegExp('/^<\?xml.+>[\s\S]+<\/.+>$/', $actual);
    }
}
