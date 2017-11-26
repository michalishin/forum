<?php

namespace Tests\Unit;

use App\Rules\Spam\SpamInspections;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /**
     * @var SpamInspections
     */
    private $spam;

    public function setUp()
    {
        parent::setUp();

        $this->spam = new SpamInspections();
    }

    /** @test */
    public function it_should_ignore_valid_text () {
        $this->assertFalse($this->spam->detect('Innocent reply here'));
    }

    /** @test */
    public function it_can_detect_invalid_keywords()
    {
        $this->assertTrue($this->spam->detect('yahoo customer support'));
    }

    /** @test */
    public function it_can_detect_key_hold_downs()
    {
        $this->assertTrue($this->spam->detect('Hello world aaaaa'));
    }
}
