<?php

namespace ZnKaz\Egov\Tests\Unit;

use Illuminate\Support\Collection;
use ZnCore\Base\Encoders\XmlEncoder;
use ZnCore\Base\Enums\RegexpPatternEnum;
use ZnKaz\Egov\Qr\Encoders\Base64Encoder;
use ZnKaz\Egov\Qr\Encoders\ImplodeEncoder;
use ZnCore\Base\Encoders\ZipEncoder;
use ZnKaz\Egov\Qr\Factories\EncoderServiceFactory;
use ZnKaz\Egov\Qr\Libs\ClassEncoder;
use ZnKaz\Egov\Qr\Services\EncoderService;
use ZnKaz\Egov\Qr\Wrappers\JsonWrapper;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;
use ZnKaz\Egov\Wrappers\XmlWrapper;

class EgovTest extends BaseTest
{

    public function testEgov()
    {
        $encoded = [
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><BarcodeElement xmlns="http://barcodes.pdf.shep.nitec.kz/"><creationDate>2020-11-24T12:41:36.970+06:00</creationDate><elementData>lWhhdi3vAtfdw8d5gkJFwc5vXdxHIR9hYgU6RrfQKxd/gzw/wIR8G/EdapwTnNjnaAk+D1DfvIZQIU93kcYqo+MNqMC7Fw8D2NAz9DByldhfYJsXD70zDOIEPamAyp8DF4uW9rGhx5YFvaKHGnTzCFEuFv8Ygpl/9PLpYvmE8zP1/ky9/8fU+6n33EDVb/wXKffargveeF1HXjxcrF/h1YB3D6bF4qXj4j6ycoSe0EF6nWgD84+D3hEsU1EYP3CFwXweRGz7hfet9/lbsu0XiFjnHy9GycWji4cwQu6jMXA9w+L8HMFsgqn3wn+hQPpvZlh4F4PxdGX8eCf+O9ea+wCYvIMZ9wXKAHDYP/7ovTAA5LurLPpdMOATLOKjb+cPQOM54m4goGfoCeY5IDydP4AnmjsIMMw/SO1bE+k3eOrDG2MA47izBYSPVu4EfC/g70M09AMoDpQURJqrPLmABxz6k/D9j7kSESDx6muram/XDN2Et9rR8m8cqv2Wf4RY6GoqAoNvOdm0TEOR+8YM16CoOV1LDTB93RoBsMHrDNer/niqprktML6lUGFzC62QISqCRtIqxLcxiK2R4SXSrYE10t7RLdsJhUMUubX8RS1sV7U9baTBNAw0qsLOhh9XHYaXvQelba/fvtHzWo40c6L1raGmbtnLABZO39Lgj+bmHXTWnDVAXGoju8SrwHlD12znP87aMle+uabcH2upGD/dy+23h+18u1yMVBOhQqvfnVrT3vBwwuTpiSVXMlRmk+gojR0MbVUZL1xm3r+9UkuXZ+5rNPuEzjT5ttsY18KTdF5xZVOMt8lZROe5aqc94xuq5rR7iXI/l4/WxXLmsJ2PWmNXyVW7dbo=</elementData><elementNumber>3</elementNumber><elementsAmount>5</elementsAmount><FavorID>10100464053940</FavorID></BarcodeElement>',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><BarcodeElement xmlns="http://barcodes.pdf.shep.nitec.kz/"><creationDate>2020-11-24T12:41:37.034+06:00</creationDate><elementData>LE2b7Z4by7NC7qBSqVe0piG5zM7OpesVX8h9QZteQtmNkInLdkE3nDZyjD0odGA8URDSiTrHsflNnXEFltGFRqmULe0OpZstxrhZyBb2NCVay5I33UTFdnmplS9YYplxLZaR0hwrMW5jli6KTC/LUI002xW5GmUdVnmWY/VSk2VEsZwouGR+IlkOZ5CFSW1g8NLA4fbJyL40SHASmQ9J/RI3Igu8ZCU4iyzYOpmPSVYJ5PM8khmRQ0svpNVKg5KGIhve5euCK/K9w1I9DVf4nbHQ2uGVNVfXs3tiTXfzeotvShLPs4Tvu5CTeiWuhXybDlcTsK+J1FdhL4/32mK152ZcrJfmWf61emJVdzO+7UzaTeRrjTBd4hlKrEszEa6lmXDIzZi8n4tWnek5stjKRUQhR/trlqhrMWmIbU6kQQnlA8UfQlhklAezxClkfhflw8TXxXoPTlLA8iNp6CB9kFW5JsojXDUyX5FMyDXgrJIFE9nrkoWi1HM4SeCKWB/FAzlvoDyD/eV+E8engu1CXEf2wQZcicWV9/3BnuBwe8gf5KRG5vfRGTdRHKOXthDGBl6b8sgn1u87vk+zJvh2/ZqQFrbqeE9FMvvo2l1gaIMtDZ0PrhuEHXCsnEGRZxIcnMFhGeWeR3XAwDk0DrMzpo3zzVli2vHzB/5wzaFzRH6GM9YWuUaWqx3wvC4WoL5Zps60WN060N0wz+lpVPPsyj0P9wxDigyb5ipjero7cJSIPS2KhLI7YKqNBGvkebkX6fYOjM1SrrObOyhIzWxir1KgKrVJTtulohIhl3SyQfa6h6FC5LDCU1W10qlTs5hIVq2wwEZnHMdoisujGKtkhZFyBADhGb3DiJw=</elementData><elementNumber>4</elementNumber><elementsAmount>5</elementsAmount><FavorID>10100464053940</FavorID></BarcodeElement>',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><BarcodeElement xmlns="http://barcodes.pdf.shep.nitec.kz/"><creationDate>2020-11-24T12:41:37.098+06:00</creationDate><elementData>jvpOzUtSV2TjWY6zs4zUyLCumGZ1HeNkXAn6etm3YpqRBFZk4lhPcCVBZGSGzTgHymbPzUnYR5llW+lMxsoeJuJFLb9H1/o9go4fMJuEPK7Ku3ExE/b1mequmGm4DbeVW9jnmDoriFx6BYvAZrpNS81V3bIRnyhZZ1gcqBPViIw0I7Gv5ERXcFtCwW2xrNTIwQyCvnObcq5KKrw1KYbYkVyLmJ1QayyHrEkrxM7EjB93zq1WxXTcrcts0UoPssKgS6o5Jlqcgt2BO77ia9yiM3ttOjFTuQjsT7MCvSZ/WDTXfXUGpe4urU6UkDRuhfK2mHGx36JebYoZ0W3IbMVK09l89i38ZhJgt0QWB6VpR2j1L+Oj4Z5Pv1mHZ/fhTEesns6wkgIl4Oe+uJI7kSmsxuTKdG8M8dLFgRMFP33FlNZjoptTkRNentWMYbvd9uVZdejSTOEia3ZEhlzvEb0BPZBmSprQnZjdDFkb27E2G6FCbaqehjksWGSn09mrh9RYuewybHOTm5Qr4kEhZ0TStDMTIwm5LdKVSlRUwsLmzUS3o03afL2TyHb0BcldJa7LRZ/aiFXSWyPF1PL/axZ/dfkXUEsHCPnzjynJCwAAPxsAAFBLAQIUABQACAgIADJleFH5848pyQsAAD8bAAADAAAAAAAAAAAAAAAAAAAAAABvbmVQSwUGAAAAAAEAAQAxAAAA+gsAAAAA</elementData><elementNumber>5</elementNumber><elementsAmount>5</elementsAmount><FavorID>10100464053940</FavorID></BarcodeElement>',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><BarcodeElement xmlns="http://barcodes.pdf.shep.nitec.kz/"><creationDate>2020-11-24T12:41:36.844+06:00</creationDate><elementData>UEsDBBQACAgIADJleFEAAAAAAAAAAAAAAAADAAAAb25l7VlZc9vWFf4rHOUlGVnEwl2hmMHCBSRBEtws8g0EIBAiCVAESIh8cuxMm9adqRs3ThsnaZL2rQ+Vt0S1tczkF4DqP+gv6bkXpEzKVuy0nT50MvQQxL1n+c6555wPkJMfHA76gYk2sg3L3NmgguRGQDMVSzVMfWejUc9sxTc+SCVNm94eafbQMm0tABqmvQ1LOxtdxxluE4ThjJRgb0boHXVkEppuTQiaJOMbvuibxAjFGgws05kONXvj0nroUs113aAbClojHYmTBJkgQEi1Df2djSW0g7FmO6XxoKONUhRJkWQ4GiYjoUSYTBKvSmAlVVP68kg2HUFNxRMkfMIRUI36Cqu7qz542dFSNEmTWxS1RYfrFL0dprapRJBMxDbJ6Da57hCLr+rXprajDcBopVytM8U14cs9rGA7sjO2/d9wIFqKqVSq5Waa93XwEt405cHSxzjlfeYdeefzW97Z/MOA93h+1zv3zrwX3qn3zDtdeBv70r1eyvvm4hHIHnmn87tI/mx+CzROvaew9MJ7Or/ra4Ck/8N3RVyF1xnbhqnZNkQr+yvMRDb6csfoG870puF0c4btWKNpBQrNMquLSvJFa2BIG2imk9E0tdKXFS3l/cU7Bv/HCEUAQ38OQZzA5xkA/CV8f4hXnnhnAZB5Pv8FyJ4hQQjmdLlx5j2a/wrJzm97j72jwA9/9e6jtMzvoPjAkL8BeYHP0fy3AWQTDJzj7yNkFBZB6084RY/h9jZIv5j/ZqEJPp7CLSg+wRLf4byd/gCYz9Hm58gLrDxBq7B3DGn2gf8do4MQj7CpYz+lr8nEeoZ+pMA=</elementData><elementNumber>1</elementNumber><elementsAmount>5</elementsAmount><FavorID>10100464053940</FavorID></BarcodeElement>',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><BarcodeElement xmlns="http://barcodes.pdf.shep.nitec.kz/"><creationDate>2020-11-24T12:41:36.907+06:00</creationDate><elementData>r8qsK16p2tVCXZfBavyy9lPJogxNgw7d+513D9L3NXx/4X3lfeLdSxKXm8mMMVrKfQYSX3t/8L5EEi/Xk6Khqn3Nl/kUJD6H7y+xJSy5sp1krZHJOykqEU9skejfJhnDeBcbPvAVlGu3glB6TT+vba8rsMbI6eLoX/V4jeAVjyqkDwqdt5RUsg5jTFAXY+zQNq4ZYxSxKxZrSlcbyFuGCc1kKhoMP9vYNo3+zoYzGmsbRCq5OE2SpuN0IkJGksTyfAXbHmvXnOvLvavBryJFOyJ0raxrlZGlwC8Y+tCb4z7k3le8bhvrljv7muLUjMGwrwnmngXDwF+yy3vVUrkPc4zXbGVkDB0gl5T3CVQ66qxj6GCYN4smmP8atff89vuo/R7jvoSGQH3zfoBMbFNhepuKh7bJSHSb2o4liVWTyaKlyL5x3E7B63sO+vsG9OlrJI5uBOa3trzTABTtCZ4Gx1jI+x4wPkLgAlD8x6B9AjhvgYWvQP8RngZHWxSsPw0GqOgNjD8YiN0IvIvL+x5Fk1QITjoWjlORKE2+Bx2zxJusGnrXqRvD1OV0OQsAUJSQ1bF0tpwPl/LJkqZO8F3GGg1S0EFoyuHx9RRP+mc4ixAtTcShXNakfbesphuQsm/QSAOlZ6+ONfh3voD1DA3gI/wF+JD5py/l8Jj7Ho1gNH7ndwLv/vOjB4EQiZjkNiQlSFJByEIMZf49yNDvwchtZO0YcdMtMHYCwPGPJ5BQsLucvCCBSHWhTiVwXa+g9yNJm2oKjOKBjxDNb8EpwHw/R+yx5fMgHsrfgb0YEX+Zo79fKbf5XR87nQj54OlYkKT/ffB0/FKdCm2H6AV4BDhJXG0T4pp2In4ilb4=</elementData><elementNumber>2</elementNumber><elementsAmount>5</elementsAmount><FavorID>10100464053940</FavorID></BarcodeElement>',
        ];

        $encoderService = EncoderServiceFactory::createServiceForEgov();
        $decoded = $encoderService->decode(new Collection($encoded));

        $expected = file_get_contents(__DIR__ . '/../data/xml/egovExample.xml');
        $this->assertEquals($expected, $decoded);
    }

    public function testXmlBase64Zip()
    {
        $xmlFile = __DIR__ . '/../data/xml/example.xml';
        $encoderService = EncoderServiceFactory::createServiceForEgov();

        $data = file_get_contents($xmlFile);
        $encodedCollection = $encoderService->encode($data);

        foreach ($encodedCollection as $i => $item) {
            $this->assertBarCode($item, $i);
        }
        $decoded = $encoderService->decode($encodedCollection);
        $this->assertEquals(5, $encodedCollection->count());
        $this->assertEquals($data, $decoded);
    }

    private function assertBarCode(string $item, int $i)
    {
        $xmlEncoder = new XmlEncoder();
        $array = $xmlEncoder->decode($item)['BarcodeElement'];
        $this->assertArraySubset([
            "@xmlns" => "http://barcodes.pdf.shep.nitec.kz/",
            "elementsAmount" => "5",
        ], $array);
        $this->assertDateTimeString($array['creationDate']);
        $this->assertEquals($i + 1, $array['elementNumber']);
        $this->assertRegExp('/^\d{14}$/', $array['FavorID']);
        $this->assertRegExp('/^' . RegexpPatternEnum::BASE_64 . '$/', $array['elementData']);
        $b64Decoded = base64_decode($array['elementData']);
        $this->assertNotEmpty($b64Decoded);
        if ($array['elementNumber'] == 1) {
            $this->assertZipContent($b64Decoded);
        }
    }
}
