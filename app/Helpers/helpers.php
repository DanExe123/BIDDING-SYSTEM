<?php

if (!function_exists('convertNumber')) {
    function convertNumber($number)
    {
        $dictionary  = [
            0       => 'zero',
            1       => 'one',
            2       => 'two',
            3       => 'three',
            4       => 'four',
            5       => 'five',
            6       => 'six',
            7       => 'seven',
            8       => 'eight',
            9       => 'nine',
            10      => 'ten',
            11      => 'eleven',
            12      => 'twelve',
            13      => 'thirteen',
            14      => 'fourteen',
            15      => 'fifteen',
            16      => 'sixteen',
            17      => 'seventeen',
            18      => 'eighteen',
            19      => 'nineteen',
            20      => 'twenty',
            30      => 'thirty',
            40      => 'forty',
            50      => 'fifty',
            60      => 'sixty',
            70      => 'seventy',
            80      => 'eighty',
            90      => 'ninety',
            100     => 'hundred',
            1000    => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
        ];

        if ($number < 21) {
            return $dictionary[$number];
        } elseif ($number < 100) {
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            return $dictionary[$tens] . ($units ? '-' . $dictionary[$units] : '');
        } elseif ($number < 1000) {
            $hundreds = (int)($number / 100);
            $remainder = $number % 100;
            return $dictionary[$hundreds] . ' hundred' . ($remainder ? ' ' . convertNumber($remainder) : '');
        } else {
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $unitName = $baseUnit == 1000 ? 'thousand' : ($baseUnit == 1000000 ? 'million' : 'billion');
            return convertNumber($numBaseUnits) . ' ' . $unitName . ($remainder ? ' ' . convertNumber($remainder) : '');
        }
    }
}

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $number = round($number, 2); // round to 2 decimals
        $integerPart = floor($number);
        $decimalPart = round(($number - $integerPart) * 100);

        $words = convertNumber($integerPart);

        // Only add decimal if > 0
        if ($decimalPart > 0) {
            $words .= " and " . convertNumber($decimalPart) . " centavos";
        }

        return ucfirst($words);
    }
}
