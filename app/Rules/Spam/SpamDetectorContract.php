<?php
/**
 * Created by PhpStorm.
 * User: vmikhalishinamd
 * Date: 25.11.17
 * Time: 19:31
 */

namespace App\Rules\Spam;


interface SpamDetectorContract
{
    public function detect(string $body) : bool ;
}