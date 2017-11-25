<?php


namespace App\Inspections;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Spam
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
            if (! ($instance instanceof SpamDetectorContract))
                throw new BadRequestHttpException('Invalid spam detector');
            if ($instance->detect($body)) {
                throw new \Exception('Spam detected');
            }
        }
        return false;
    }
}