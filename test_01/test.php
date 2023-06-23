<?php

include_once __DIR__ . '/vendor/autoload.php';

use Tests\Test as UnitTest;

class Test
{
    public function __construct()
    {
        $unit = new UnitTest;
        $unit->handler();
    }
}

new Test();
