<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase
{
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid($value, $expectedResult)
    {
        $creditCardNumberValidator = new CreditCardNumberValidator($value);

        $isValid = $creditCardNumberValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public static function valueProvider()
    {
        return [
            'shouldBeValidWhenValueIsANumber' => [
                'value' => (int) fake()->creditCardNumber('Visa'),
                'expectedResult' => true
            ],
            'shouldBeValidWhenValueIsACreditCardAsString' => [
                'value' => fake()->creditCardNumber('Visa'),
                'expectedResult' => true
            ],
            'shouldNotBeValidWhenValueIsNotACreditCard' => [
                'value' => fake()->randomNumber(5),
                'expectedResult' => false
            ],
            'shouldNotBeValidWhenValueIsEmpty' => [
                'value' => '',
                'expectedResult' => false
            ]
        ];
    }
}