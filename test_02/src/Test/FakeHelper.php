<?php

use Faker\Factory;
use Faker\Generator;

if (!function_exists('fake')) {
    /**
     * Instance fake generater class
     *
     * @return Faker\Generator
     */
    function fake(): Generator
    {
        $factory = new Factory();
        return $factory->create();
    }
}
