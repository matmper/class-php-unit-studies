<?php

include_once __DIR__ . '/vendor/autoload.php';

use App\DiscountCalculator;

class App
{
    public function __construct()
    {
        $discountCalculator = new DiscountCalculator();
        echo $discountCalculator->apply(130);
    }
}


new App();