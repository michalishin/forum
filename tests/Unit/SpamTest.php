<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /**
     * @var Spam
     */
    private $spam;

    public function setUp()
    {
        parent::setUp();

        $this->spam = new Spam();
    }

    /** @test */
    public function it_should_ignore_valid_text () {
        $this->assertFalse($this->spam->detect('Innocent reply here'));
    }

    /** @test */
    public function it_can_detect_invalid_keywords()
    {
        $this->expectException(\Exception::class);

        $this->spam->detect('yahoo customer support');

    }

    /** @test */
    public function it_can_detect_key_hold_downs()
    {
        $this->spam = new Spam();

        $this->expectException(\Exception::class);

        $this->spam->detect('Hello world aaaaa');
    }
}
