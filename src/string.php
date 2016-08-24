<?php

/**
 * Generate random string (password) from a different charset based on $secLevel.
 * $secLevel=0 [a-z] charset.
 * $secLevel=1 [a-z0-9] charset.
 * $secLevel=2 [A-Za-z0-9] charset.
 * $secLevel=3 [A-Za-z0-9-_$!+&%?=*#@] charset.
 * @param int $length
 * @param int $secLevel
 * @return string
 */
function generateRandomPassword(int $length = 10, int $secLevel = 2) : string
{
    $charset = 'abcdefghijklmnopqrstuvwxyz';
    if ($secLevel == 1) {
        $charset = 'abcdefghijklmnopqrstuvwxyz1234567890';
    } elseif ($secLevel == 2) {
        $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    } elseif ($secLevel >= 3) {
        $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890-_$!+&%?=*#@';
    }

    return generateRandomString($length, '', $charset);
}

/**
 * Generate random string from [0-9A-Za-z] charset.
 * You may extend charset by passing $extChars (i.e. add these chars to existing).
 * Ex.: $extChars='-_$!' implies that the final charset is [0-9A-Za-z-_$!]
 * If $newChars is set, the default charset are replaced by this and $extChars was ignored.
 * Ex.: $newChars='0123456789' implies that the final charset is [0123456789]
 * @param int $length
 * @param string $extChars
 * @param string $newChars
 * @return string
 */
function generateRandomString(int $length = 10, string $extChars = '', string $newChars = '') : string
{
    if ($length < 1) {
        $length = 1;
    }

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($newChars !== null && $newChars != '') {
        $characters = $newChars;
    } elseif ($extChars !== null && $extChars != '') {
        $characters .= $extChars;
    }

    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
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

if (!function_exists('preg_replace_sub')) {
    /**
     * Replace a given pattern with each value in the array in sequentially.
     *
     * @param  string $pattern
     * @param  array $replacements
     * @param  string $subject
     * @return string
     */
    function preg_replace_sub($pattern, &$replacements, $subject)
    {
        return preg_replace_callback($pattern, function () use (&$replacements) {
            return array_shift($replacements);
        }, $subject);
    }
}

if (!function_exists('snake_case')) {
    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param  string $delimiter
     * @return string
     */
    function snake_case($value, $delimiter = '_')
    {
        $snakeCache = [];
        $key = $value . $delimiter;
        if (isset($snakeCache[$key])) {
            return $snakeCache[$key];
        }
        if (!ctype_lower($value)) {
            $value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1' . $delimiter, $value));
        }
        return $snakeCache[$key] = $value;
    }
}

if (!function_exists('str_random')) {
    /**
     * Generate a more truly "random" alpha-numeric string with openssl.
     * If openssl_random_pseudo_bytes not exists, use simple legacy function
     *
     * @param  int $length
     * @return string
     *
     * @throws \RuntimeException
     */
    function str_random(int $length = 16)
    {
        if ($length < 0) {
            $length = 16;
        }

        if (!function_exists('openssl_random_pseudo_bytes')) {
            return generateRandomString($length);
        }
        $bytes = openssl_random_pseudo_bytes($length * 2);
        if ($bytes === false) {
            throw new RuntimeException('Unable to generate random string.');
        }
        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    }
}

if (!function_exists('ends_with')) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function ends_with($haystack, $needles)
    {
        if (isNullOrEmpty($haystack) || isNullOrEmpty($needles)) {
            return false;
        }

        foreach ((array)$needles as $needle) {
            if ((string)$needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        if (isNullOrEmpty($haystack) || isNullOrEmpty($needles)) {
            return false;
        }

        foreach ((array)$needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('str_contains')) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function str_contains($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('str_finish')) {
    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string $value
     * @param  string $cap
     * @return string
     */
    function str_finish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');
        return preg_replace('/(?:' . $quoted . ')+$/', '', $value) . $cap;
    }
}

if (!function_exists('str_is')) {
    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string $pattern
     * @param  string $value
     * @return bool
     */
    function str_is($pattern, $value)
    {
        if ($pattern == $value) {
            return true;
        }
        $pattern = preg_quote($pattern, '#');
        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('\*', '.*', $pattern) . '\z';
        return preg_match('#^' . $pattern . '#', $value) === 1;
    }
}
if (!function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param  string $value
     * @param  int $limit
     * @param  string $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        return rtrim(mb_substr($value, 0, $limit, 'UTF-8')) . $end;
    }
}

if (!function_exists('str_replace_array')) {
    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param  string $search
     * @param  array $replace
     * @param  string $subject
     * @return string
     */
    function str_replace_array($search, array $replace, $subject)
    {
        foreach ($replace as $value) {
            $subject = preg_replace('/' . $search . '/', $value, $subject, 1);
        }
        return $subject;
    }
}

if (!function_exists('studly_case')) {
    /**
     * Convert a value to studly caps case.
     *
     * @param  string $value
     * @return string
     */
    function studly_case($value)
    {
        $studlyCache = [];
        $key = $value;
        if (isset($studlyCache[$key])) {
            return $studlyCache[$key];
        }
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        return $studlyCache[$key] = str_replace(' ', '', $value);
    }
}

if (!function_exists('studly')) {
    /**
     * Convert a value to studly caps case.
     * Alias of studly_case
     *
     * @param  string $value
     * @return string
     */
    function studly($value)
    {
        return studly_case($value);
    }
}

if (!function_exists('camel_case')) {
    /**
     * Convert a value to camel case.
     *
     * @param  string $value
     * @return string
     */
    function camel_case($value)
    {
        $camelCache = [];
        if (isset($camelCache[$value])) {
            return $camelCache[$value];
        }
        return $camelCache[$value] = lcfirst(studly($value));
    }
}

/**
 * Replace underscores with dashes in the string.
 * @param string $word
 * @return string
 */
function underscore2dash(string $word) : string
{
    return str_replace('_', '-', $word);
}

/**
 * Make an underscored, lowercase form from the expression in the string.
 * @param string $word
 * @return string
 */
function dash2underscore(string $word) : string
{
    $word = preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2', $word);
    $word = preg_replace('/([a-z])([A-Z])/', '\1_\2', $word);
    return str_replace('-', '_', strtolower($word));
}

/**
 * Replace multiple spaces with one space.
 * @param string $str
 * @return string
 */
function str_replace_multiple_space(string $str) : string
{
    return preg_replace('/\s+/', ' ', $str);
}

/**
 * Replace last occurrence ($search) of a string ($subject) with $replace string.
 * @param string $search
 * @param string $replace
 * @param string $subject
 * @return string
 */
function str_replace_last(string $search, string $replace, string $subject) : string
{
    if ($search == '') {
        return $subject;
    }
    $position = strrpos($subject, $search);
    if ($position === false) {
        return $subject;
    }
    return substr_replace($subject, $replace, $position, strlen($search));
}

/**
 * Get a segment from a string based on a delimiter.
 * Returns an empty string when the offset doesn't exist.
 * Use a negative index to start counting from the last element.
 *
 * @param string $delimiter
 * @param int $index
 * @param string $subject
 *
 * @return string
 * @see https://github.com/spatie/string/blob/master/src/Str.php
 */
function segment($delimiter, $index, $subject)
{
    $segments = explode($delimiter, $subject);
    if ($index < 0) {
        $segments = array_reverse($segments);
        $index = (int)abs($index) - 1;
    }
    $segment = isset($segments[$index]) ? $segments[$index] : '';
    return $segment;
}

/**
 * Get the first segment from a string based on a delimiter.
 *
 * @param string $delimiter
 * @param string $subject
 *
 * @return string
 * @see https://github.com/spatie/string/blob/master/src/Str.php
 */
function firstSegment($delimiter, $subject) : string
{
    return segment($delimiter, 0, $subject);
}

/**
 * Get the last segment from a string based on a delimiter.
 *
 * @param string $delimiter
 * @param string $subject
 *
 * @return string
 * @see https://github.com/spatie/string/blob/master/src/Str.php
 */
function lastSegment($delimiter, $subject) : string
{
    return segment($delimiter, -1, $subject);
}

/**
 * Return true if $subject is null or empty string ('').
 * @param $subject
 * @return bool
 */
function isNullOrEmpty($subject) : bool
{
    return $subject === null || $subject == '';
}

/**
 * Return true if $subject is not null and is not empty string ('').
 * @param $subject
 * @return bool
 */
function isNotNullOrEmpty($subject) : bool
{
    return !isNullOrEmpty($subject);
}
