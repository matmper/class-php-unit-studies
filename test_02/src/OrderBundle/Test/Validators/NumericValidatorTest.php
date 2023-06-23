<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid($value, $expectedResult)
    {
        $numericValidator = new NumericValidator($value);

        $isValid = $numericValidator->isValid();

        $this->assertEquals($expectedResult, $isValid);
    }

    public static function valueProvider()
    {
        return [
            'shouldBeValidWhenValueIsANumber' => [
                'value' => fake()->randomFloat(2, 1),
                'expectedResult' => true
            ],
            'shouldBeValidWhenValueIsANumericString' => [
                'value' => (string) fake()->randomFloat(2, 1),
                'expectedResult' => true
            ],
            'shouldNotBeValidWhenValueIsNotANumber' => [
                'value' => fake()->word(),
                'expectedResult' => false
            ],
            'shouldNotBeValidWhenValueIsEmpty' => [
                'value' => '',
                'expectedResult' => false
            ],
        ];
    }
}