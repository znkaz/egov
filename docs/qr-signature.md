# Генерация QR-кодов из XML-документа

Генерация QR-кодов нужна для верификации подлинности распечатанного документа.

Верификатор подлинности можете найти тут - https://sigex.kz/support/register-egov-document/

Желательно, QR-изображения в PDF-документе делать побольше,
так как валидатор не всегда может прочитать маленькие QR-коды.

На входе подписанный XML-документ, в результате - коллекция сущностей с изображениями QR-кодов.

Формат картинки можно задать в 5-м параметре.
Поддерживаемые форматы изображений - \ZnLib\QrBox\Enums\ImageExtensionEnum.

Пример генерации:

```php
use ZnKaz\Egov\Facades\QrFacade;

$inputXml = '<?xml version="1.0" encoding="UTF-8"?>
<ns2:response xmlns:ns2="http://itrc.kz/gbdrn/egov/2008" xmlns="http://itrc.kz/gbdrn/egov/2008/commontypes" xmlns:ns3="http://www.w3.org/2000/09/xmldsig#">
...
</ns2:response>';
$qrCollection = QrFacade::generateQrCode($inputXml);
print_r($qrCollection);
```
