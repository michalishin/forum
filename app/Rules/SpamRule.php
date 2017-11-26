<?php

namespace App\Rules;

use App\Rules\Spam\SpamInspections;

class SpamRule
{
    /**
     * @var SpamInspections
     */
    private $spam;

    /**
     * Create a new rule instance.
     *
     * @param SpamInspections $spam
     */
    public function __construct(SpamInspections $spam)
    {
        $this->spam = $spam;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !$this->spam->detect($value);
    }
}
