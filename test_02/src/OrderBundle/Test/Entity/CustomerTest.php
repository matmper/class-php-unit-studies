<?php

namespace OrderBundle\Test\Entity;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /**
     * @test
     * @dataProvider customerAllowedDataProvider
     */
    public function isAllowedToOrder($isActive, $isBlocked, $expectedAlloed)
    {
        $customer = new Customer(
            $isActive,
            $isBlocked,
            fake()->name(),
            fake()->phoneNumber()
        );

        $isAllowed = $customer->isAllowedToOrder();

        $this->assertEquals($expectedAlloed, $isAllowed);
    }

    public static function customerAllowedDataProvider()
    {
        return [
            'shouldBeAllowedWhenIsActiveAndNotBlocked' => [
                'isActive' => true,
                'isBlocked' => false,
                'expectedAllowed' => true
            ],
            'shouldNotBeAllowedWhenIsActiveButIsBlocked' => [
                'isActive' => true,
                'isBlocked' => true,
                'expectedAllowed' => false
            ],
            'shouldNotBeAllowedWhenIsNotActive' => [
                'isActive' => false,
                'isBlocked' => false,
                'expectedAllowed' => false
            ],
            'shouldNotBeAllowedWhenIsNotActiveAndIsBlocked' => [
                'isActive' => false,
                'isBlocked' => true,
                'expectedAllowed' => false
            ]
        ];
    }
}

