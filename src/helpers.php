<?php

/**
 * @param int $red
 * @param int $green
 * @param int $blue
 * @return string
 * @see https://github.com/spatie-custom/blender/blob/master/app/Foundation/helpers.php
 */
function rgb2hex(int $red, int $green, int $blue):  string
{
    return '#' . collect([$red, $green, $blue])
        ->map(function (int $decimal):  string {
            return str_pad(dechex($decimal), 2, STR_PAD_LEFT);
        })
        ->implode('');
}

/**
 * @param int $val
 * @param int $precision
 * @param string $simbol
 * @return string
 */
function format_money($val = 0, $precision = 2, $simbol = "")
{
    return "$simbol " . number_format($val, $precision, ',', '.');
}

/**
 * Format float 1125.86 into string '&euro 1.125,86'
 * @param int $val
 * @return string
 */
function format_euro($val = 0)
{
    return "&euro; " . number_format($val, 2, ',', '.');
}

/**
 * Given a number, return the number + 'th' or 'rd' etc
 * @param $cdnl
 * @return string
 */
function ordinal($cdnl)
{
    $test_c = abs($cdnl) % 10;
    $ext = ((abs($cdnl) % 100 < 21 && abs($cdnl) % 100 > 4) ? 'th'
        : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1)
            ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
    return $cdnl . $ext;
}

if ( ! function_exists('value'))
{
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
if ( ! function_exists('with'))
{
    /**
     * Return the given object. Useful for chaining.
     *
     * @param  mixed  $object
     * @return mixed
     */
    function with($object)
    {
        return $object;
    }
}
