<?php

namespace ZnKaz\Egov\Qr\Libs;

use ZnCore\Base\Helpers\InstanceHelper;
use ZnKaz\Egov\Qr\Wrappers\WrapperInterface;

class WrapperDetector
{

    private $wrappers;

    public function __construct(array $wrappers)
    {
        $this->wrappers = $wrappers;
    }

    /**
     * @param string $encoded
     * @return WrapperInterface
     * @throws \Exception
     */
    public function detect(string $encoded): WrapperInterface
    {
        foreach ($this->wrappers as $wrapperClass) {
            $wrapperInstance = $this->createEncoder($wrapperClass);
            $isDetected = $wrapperInstance->isMatch($encoded);
            if ($isDetected) {
                return $wrapperInstance;
            }
        }
        throw new \Exception('Wrapper not detected!');
    }

    private function createEncoder($wrapperClass): WrapperInterface
    {
        return InstanceHelper::create($wrapperClass);
    }
}