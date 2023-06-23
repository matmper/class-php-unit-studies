<?php

namespace Tests;

use App\DiscountCalculator;

class DiscountCalculatorTest
{
    /**
     * Teste method: should apply discount
     *
     * @return void
     */
    public function ShouldApply_WhenValueIsAboveTheMinimumTest(): void
    {
        $discountCalculator = new DiscountCalculator();

        $totalValue = 130;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        $expectedValue = $totalValue - $discountCalculator::DISCOUNT_VALUE;

        $this->assertEquals($expectedValue, $totalWithDiscount);
    }

    /**
     * Teste method: should not apply
     *
     * @return void
     */
    public function ShouldNotApply_WhenValueIsBellowTheMinimumTest(): void
    {
        $discountCalculator = new DiscountCalculator();

        $totalValue = 90;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        $this->assertEquals($totalValue, $totalWithDiscount);
    }

    /**
     * @param integer $expectedValue
     * @param integer $actualValue
     * @return void
     */
    private function assertEquals(int $expectedValue, int $actualValue): void
    {
        if ($expectedValue !== $actualValue) {
            throw new \Exception('Expected: ' . $expectedValue . ' but got: ' . $actualValue);
        }
    }
}
