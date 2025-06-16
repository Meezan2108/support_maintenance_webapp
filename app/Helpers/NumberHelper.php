<?php

namespace App\Helpers;

use Carbon\Carbon;

class NumberHelper
{
    public static function numberToRoman($num)
    {
        $romanNumerals = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        $result = '';

        foreach ($romanNumerals as $roman => $value) {
            $numOfSymbols = intval($num / $value);
            $result .= str_repeat($roman, $numOfSymbols);
            $num -= $numOfSymbols * $value;
        }

        return $result;
    }

    public static function numberToAlphabet($num)
    {
        $alphabet = range('A', 'Z');

        $result = '';

        while ($num > 0) {
            $remainder = ($num - 1) % 26;
            $result = $alphabet[$remainder] . $result;
            $num = floor(($num - 1) / 26);
        }

        return $result;
    }
}
