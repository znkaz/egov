<?php

namespace ZnKaz\Egov\Qr\Libs;

use Illuminate\Support\Collection;
use ZnCore\Base\Encoders\AggregateEncoder;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;

class ClassEncoder
{

    private $assoc = [];

    public function __construct(array $assoc)
    {
        $this->assoc = $assoc;
    }

    private function encoderToClass(string $name)
    {
        return ArrayHelper::getValue($this->assoc, $name);
    }

    public function encodersToClasses(array $names): AggregateEncoder
    {
        $classes = [];
        foreach ($names as $name) {
            $classes[] = $this->encoderToClass($name);
        }
        $encoders = new AggregateEncoder(new Collection($classes));
        return $encoders;
    }
}