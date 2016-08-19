<?php

/**
 * Strip new line breaks from a string
 * @param $str
 * @return string|array
 */
function strip_nl($str)
{
    return str_replace("\n", "", str_replace("\r", "", $str));
}

/**
 * Generate random string from [0-9A-Za-z] charset.
 * You may extend charset by passing $extChars.
 * Ex.: $extChars='-_&$!+'
 * @param int $length
 * @param string $extChars
 * @return string
 */
function generateRandomString(int $length = 10, string $extChars = '') : string
{
    if ($length < 1) {
        $length = 1;
    }

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($extChars !== null && $extChars != '') {
        $characters .= $extChars;
    }

    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

/**
 * Javascript escape
 * @param string $str
 * @return string
 * @source https://github.com/rtconner/laravel-plusplus/blob/laravel-5/src/plus-functions.php
 */
function jse(string $str) : string
{
    if ($str === null || $str == '') {
        return '';
    }
    $str = str_replace("\n", "", str_replace("\r", "", $str));
    return addslashes($str);
}

if (!function_exists('gravatar')) {
    /**
     * Get a Gravatar URL from email.
     *
     * @param string $email The email address
     * @param int $size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $rating (inclusive) [ g | pg | r | x ]
     * @return string
     * @source http://gravatar.com/site/implement/images/php/
     */
    function gravatar($email, $size = 80, $default = 'mm', $rating = 'g')
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$size&d=$default&r=$rating";
        return $url;
    }
}

/**
 * *****************************************************
 * LARAVEL STRING HELPERS
 * With some adjustments
 * *****************************************************
 */

if ( ! function_exists('e'))
{
    /**
     * Escape HTML entities in a string.
     *
     * @param  string  $value
     * @return string
     */
    function e($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }
}

if ( ! function_exists('preg_replace_sub'))
{
    /**
     * Replace a given pattern with each value in the array in sequentially.
     *
     * @param  string  $pattern
     * @param  array   $replacements
     * @param  string  $subject
     * @return string
     */
    function preg_replace_sub($pattern, &$replacements, $subject)
    {
        return preg_replace_callback($pattern, function() use (&$replacements)
        {
            return array_shift($replacements);
        }, $subject);
    }
}

if ( ! function_exists('snake_case'))
{
    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    function snake_case($value, $delimiter = '_')
    {
        $snakeCache = [];
        $key = $value.$delimiter;
        if (isset($snakeCache[$key]))
        {
            return $snakeCache[$key];
        }
        if ( ! ctype_lower($value))
        {
            $value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1'.$delimiter, $value));
        }
        return $snakeCache[$key] = $value;
    }
}

if ( ! function_exists('str_random'))
{
    /**
     * Generate a more truly "random" alpha-numeric string with openssl.
     * If openssl_random_pseudo_bytes not exists, use simple legacy function
     *
     * @param  int  $length
     * @return string
     *
     * @throws \RuntimeException
     */
    function str_random(int $length = 16)
    {
        if($length<0){
            $length = 16;
        }

        if ( ! function_exists('openssl_random_pseudo_bytes'))
        {
            return generateRandomString($length);
        }
        $bytes = openssl_random_pseudo_bytes($length * 2);
        if ($bytes === false)
        {
            throw new RuntimeException('Unable to generate random string.');
        }
        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    }
}

if ( ! function_exists('ends_with'))
{
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function ends_with($haystack, $needles)
    {
        if($haystack===null || $haystack=='' || $needles===null || $needles==''){
            return false;
        }

        foreach ((array) $needles as $needle)
        {
            if ((string) $needle === substr($haystack, -strlen($needle))) return true;
        }
        return false;
    }
}

if ( ! function_exists('starts_with'))
{
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        if($haystack===null || $haystack=='' || $needles===null || $needles==''){
            return false;
        }

        foreach ((array) $needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) === 0) return true;
        }
        return false;
    }
}

if ( ! function_exists('str_contains'))
{
    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function str_contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) !== false) return true;
        }
        return false;
    }
}

if ( ! function_exists('str_finish'))
{
    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string  $value
     * @param  string  $cap
     * @return string
     */
    function str_finish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');
        return preg_replace('/(?:'.$quoted.')+$/', '', $value).$cap;
    }
}

if ( ! function_exists('str_is'))
{
    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string  $pattern
     * @param  string  $value
     * @return bool
     */
    function str_is($pattern, $value)
    {
        if ($pattern == $value) return true;
        $pattern = preg_quote($pattern, '#');
        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('\*', '.*', $pattern).'\z';
        return (bool) preg_match('#^'.$pattern.'#', $value);
    }
}
if ( ! function_exists('str_limit'))
{
    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int     $limit
     * @param  string  $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value) <= $limit) return $value;
        return rtrim(mb_substr($value, 0, $limit, 'UTF-8')).$end;
    }
}

if ( ! function_exists('str_random'))
{
    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int  $length
     * @return string
     *
     * @throws \RuntimeException
     */
    function str_random($length = 16)
    {
        if ( ! function_exists('openssl_random_pseudo_bytes'))
        {
            throw new RuntimeException('OpenSSL extension is required.');
        }
        $bytes = openssl_random_pseudo_bytes($length * 2);
        if ($bytes === false)
        {
            throw new RuntimeException('Unable to generate random string.');
        }
        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    }
}

if ( ! function_exists('str_replace_array'))
{
    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param  string  $search
     * @param  array   $replace
     * @param  string  $subject
     * @return string
     */
    function str_replace_array($search, array $replace, $subject)
    {
        foreach ($replace as $value)
        {
            $subject = preg_replace('/'.$search.'/', $value, $subject, 1);
        }
        return $subject;
    }
}

if ( ! function_exists('studly_case'))
{
    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    function studly_case($value)
    {
        $studlyCache = [];
        $key = $value;
        if (isset($studlyCache[$key]))
        {
            return $studlyCache[$key];
        }
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        return $studlyCache[$key] = str_replace(' ', '', $value);
    }
}

/**
 * Helper functions for the helper functions, that can still be used standalone
 */
if ( ! function_exists('studly'))
{
    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    function studly($value)
    {
        $studlyCache = [];
        $key = $value;
        if (isset($studlyCache[$key]))
        {
            return $studlyCache[$key];
        }
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        return $studlyCache[$key] = str_replace(' ', '', $value);
    }
}

if ( ! function_exists('camel_case'))
{
    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    function camel_case($value)
    {
        $camelCache = [];
        if (isset($camelCache[$value]))
        {
            return $camelCache[$value];
        }
        return $camelCache[$value] = lcfirst(studly($value));
    }
}
