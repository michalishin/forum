<?php


namespace App\Rules\Spam;


class DetectHoldDown implements SpamDetectorContract
{

    public function detect(string $body): bool
    {
        return !!preg_match('/(.)\\1{4,}/', $body);
    }
}