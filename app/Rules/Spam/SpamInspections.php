<?php


namespace App\Rules\Spam;

class SpamInspections
{
    protected $detectors = [
        DetectHoldDown::class,
        InvalidKeyWords::class
    ];

    /**
     * @param string $body
     * @return bool
     * @throws \Exception
     */
    public function detect (string $body)
    {
        foreach ($this->detectors as $detector) {
            $instance = new $detector;
            if ($instance->detect($body)) {
                return true;
            }

        }
        return false;
    }
}