<?php

namespace App;

class DiscountCalculator
{
    const MINIMUM_VALUE = 100;
    const DISCOUNT_VALUE = 20;

    /**
     * Validate and apply discount
     *
     * @param integer $value
     * @return integer
     */
    public function apply(int $value): int
    {
        if ($value > self::MINIMUM_VALUE) {
            return $value - self::DISCOUNT_VALUE;
        }

        return $value;
    }

}