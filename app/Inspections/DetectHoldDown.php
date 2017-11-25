<?php


namespace App\Inspections;


class DetectHoldDown implements SpamDetectorContract
{

    public function detect(string $body): bool
    {
        return !!preg_match('/(.)\\1{4,}/', $body);
    }
}