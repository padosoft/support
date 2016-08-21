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
 * @param float $val
 * @param int $precision
 * @param string $simbol
 * @return string
 */
function format_money(float $val = 0, int $precision = 2, string $simbol = "") : string
{
    return "$simbol " . number_format($val, $precision, ',', '.');
}

/**
 * Format float 1125.86 into string '&euro 1.125,86'
 * @param float $val
 * @return string
 */
function format_euro(float $val = 0) : string
{
    return format_money($val, 2, '&euro; ');
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

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
if (!function_exists('with')) {
    /**
     * Return the given object. Useful for chaining.
     *
     * @param  mixed $object
     * @return mixed
     */
    function with($object)
    {
        return $object;
    }
}

/**
 * Set the default configuration of erro reporting for production.
 */
function setErrorReportingForProduction()
{
    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
    } elseif (version_compare(PHP_VERSION, '5.3.0') >= 0) {
        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
    } else {
        error_reporting(E_ALL ^ E_NOTICE);
    }
}

/**
 * Check if PHP script was executed by shell.
 * @return bool
 */
function isExecutedByCLI() : bool
{
    return php_sapi_name() == 'cli';
}
