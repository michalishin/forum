<?php


namespace App\Inspections;


class InvalidKeyWords implements SpamDetectorContract
{
    protected $invalidsKeyWords = [
        'yahoo customer support'
    ];

    public function detect(string $body): bool
    {
        foreach ($this->invalidsKeyWords as $keyWord) {
            if (mb_stripos($body, $keyWord) !== false) {
                return true;
            }
        }

        return false;
    }
}