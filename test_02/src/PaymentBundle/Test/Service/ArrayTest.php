<?php

namespace Test\PaymentBundle\Service;

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeEmpty()
    {
        $array = [];

        $this->assertEmpty($array);
        $this->assertTrue(is_array($array));
        $this->assertCount(0, $array);
    }

    /**
     * @test
     */
    public function shouldBeFilled()
    {
        $array = [1 => fake()->name()];

        $this->assertNotEmpty($array);
        $this->assertTrue(is_array($array));
        $this->assertCount(1, $array);
    }
}