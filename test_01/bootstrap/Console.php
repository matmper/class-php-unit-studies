<?php

/**
 * PHP Colored CLI
 * Used to log strings with custom colors to console using php
 * 
 * Copyright (C) 2013 Sallar Kaboli <sallar.kaboli@gmail.com>
 * MIT Liencesed
 * http://opensource.org/licenses/MIT
 *
 * Original colored CLI output script:
 * (C) Jesse Donat https://github.com/donatj
 */

namespace Bootstrap;

class Console
{
    static $foregroundColors = [
        'bold'         => '1',    'dim'          => '2',
        'black'        => '0;30', 'dark_gray'    => '1;30',
        'blue'         => '0;34', 'light_blue'   => '1;34',
        'green'        => '0;32', 'light_green'  => '1;32',
        'cyan'         => '0;36', 'light_cyan'   => '1;36',
        'red'          => '0;31', 'light_red'    => '1;31',
        'purple'       => '0;35', 'light_purple' => '1;35',
        'brown'        => '0;33', 'yellow'       => '1;33',
        'light_gray'   => '0;37', 'white'        => '1;37',
        'normal'       => '0;39',
    ];
    
    static $backgroundColors = [
        'black'        => '40',   'red'          => '41',
        'green'        => '42',   'yellow'       => '43',
        'blue'         => '44',   'magenta'      => '45',
        'cyan'         => '46',   'light_gray'   => '47',
    ];

    static $EOF = "\n";
    
    /**
     * Catches static calls (Wildcard)
     * @param  string $foregroundColor Text Color
     * @param  array  $args            Options
     * @return string                  Colored string
     */
    public static function __callStatic(string $foregroundColor, array $args): string
    {
        $string = $args[0];
        $coloredString = "";

        if (isset(self::$foregroundColors[$foregroundColor])) {
            $coloredString .= "\033[" . self::$foregroundColors[$foregroundColor] . "m";
        } else {
            throw new \Exception($foregroundColor . ' not a valid color');
        }
        
        array_shift($args);

        foreach ($args as $option) {
            if (isset(self::$backgroundColors[$option])) {
                $coloredString .= "\033[" . self::$backgroundColors[$option] . "m";
            }
        }

        $coloredString .= $string . "\033[0m";
        
        return $coloredString;
    }
}
