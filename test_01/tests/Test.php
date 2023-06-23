<?php

namespace Tests;

use DirectoryIterator;
use ReflectionClass;
use ReflectionMethod;
use Bootstrap\Console;

class Test
{
    /**
     * Set microtime
     *
     * @var float
     */
    private $timer;

    public function __construct()
    {
        $this->timer = microtime(true);
    }

    /**
     * Handle unit tests
     *
     * @return void
     */
    public function handler(): void
    {
        echo Console::bold("Tests started\n\n");

        foreach (new DirectoryIterator(__DIR__) as $file)
        {
            $filename = $file->getFilename();

            if (strlen($filename) <= 8 || substr($filename, -8) !== 'Test.php')
            {
                continue;
            }
        
            $className = 'Tests\\' . substr($filename, 0, -4);
            $testClass = new ReflectionClass($className);
            $testMethods = $testClass->getMethods();

            echo Console::blue("- {$className}\n");
            
            try {
                foreach ($testMethods as $method)
                {
                    if (substr($method->name, -4) !== 'Test') {
                        continue;
                    }
        
                    echo Console::blue("  - ");
                    echo Console::white("{$method->name}: ");
            
                    new ReflectionMethod($className, $method->name);
                    
                    echo Console::green("Passed\n");
                }
            } catch (\Throwable $th) {
                echo Console::red($th->getMessage() . "\n");
                throw $th;
            }
        
            $timer = number_format(microtime(true) - $this->timer, 2, '.');
    
            echo "\n";
            echo Console::bold("Tests finished: ");
            echo Console::green("Passed ");
            echo Console::white("({$timer} seconds)");
        }
    }
}
