<?php


namespace App;


class Spam
{
    public function detect (string $body) {
        $this->detectInvalidKeyword($body);

        return false;
    }

    protected function detectInvalidKeyword (string $body) {
        $invalidsKeyWords = [
            'yahoo customer support'
        ];

        foreach ($invalidsKeyWords as $keyWord) {
            if (mb_stripos($body, $keyWord) !== false) {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}