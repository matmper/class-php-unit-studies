<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;

class CreditCardExpirationValidatorTest extends TestCase
{
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid($value, $expectedResult)
    {
        $creditCardExpirationDate = new \DateTime($value);
        $creditCardExpirationValidator = new CreditCardExpirationValidator($creditCardExpirationDate);

        $isValid = $creditCardExpirationValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public static function valueProvider()
    {
        return [
            'shouldBeValidWhenDateIsNotExpired' => [
                'value' => fake()->dateTimeBetween('+1 day', '+10 year')->format('Y-m-d'),
                'expectedResult' => true
            ],
            'shouldNotBeValidWhenDateIsExpired' => [
                'value' => fake()->dateTimeBetween('-10 year', '-1 day')->format('Y-m-d'),
                'expectedResult' => false
            ],
        ];
    }
}