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

if (!function_exists('ends_with_insensitive')) {
    /**
     * Determine if a given string ends with a given substring (case insensitive).
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function ends_with_insensitive($haystack, $needles)
    {
        if (isNullOrEmpty($haystack) || isNullOrEmpty($needles)) {
            return false;
        }

        $haystack = strtolower($haystack);
        $needles = strtolower($needles);

        return ends_with($haystack, $needles);
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

if (!function_exists('starts_with_insensitive')) {
    /**
     * Determine if a given string starts with a given substring (case insensitive).
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function starts_with_insensitive($haystack, $needles)
    {
        if (isNullOrEmpty($haystack) || isNullOrEmpty($needles)) {
            return false;
        }

        $haystack = strtolower($haystack);
        $needles = strtolower($needles);

        return starts_with($haystack, $needles);
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
    function str_contains(string $haystack, $needles)
    {
        if (isNullOrEmpty($haystack)) {
            return false;
        }
        if ((is_array($needles) && isNullOrEmptyArray($needles))
            || (!is_array($needles) && isNullOrEmpty($needles))
        ) {
            return false;
        }

        foreach ((array)$needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('str_contains_insensitive')) {
    /**
     * Determine if a given string contains a given substring (case insensitive).
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function str_contains_insensitive($haystack, $needles)
    {
        if (isNullOrEmpty($haystack) || isNullOrEmpty($needles)) {
            return false;
        }

        $haystack = strtolower($haystack);
        $needles = strtolower($needles);

        return str_contains($haystack, $needles);
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
        if (isNullOrEmpty($value) || isNullOrEmpty($cap)) {
            return false;
        }

        $quoted = preg_quote($cap, '/');
        return preg_replace('/(?:' . $quoted . ')+$/', '', $value) . $cap;
    }
}

if (!function_exists('str_finish_insensitive')) {
    /**
     * Cap a string with a single instance of a given value (Case Insensitive).
     *
     * @param  string $value
     * @param  string $cap
     * @return string
     */
    function str_finish_insensitive($value, $cap)
    {
        if (isNullOrEmpty($value) || isNullOrEmpty($cap)) {
            return false;
        }

        $value = strtolower($value);
        $cap = strtolower($cap);

        return str_finish($value, $cap);
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
     * @param  string $end append in
     * @param  bool $wordsafe if set to true, remove any truncated word in the end of string so the result no breaking words.
     * @return string
     */
    function str_limit(string $value, int $limit = 100, string $end = '...', bool $wordsafe = false) : string
    {
        $limit = max($limit, 0);
        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        $string = rtrim(mb_substr($value, 0, $limit, 'UTF-8'));
        if ($wordsafe) {
            $string = preg_replace('/\s+?(\S+)?$/', '', $string);
        }
        return $string . $end;
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

if (!function_exists('str_replace_multiple_space')) {

    /**
     * Replace multiple spaces with one space.
     * If $withNbsp replaces "&nbsp;" with a single space before converts multiple sequential spaces.
     * @param string $str
     * @param bool $withNbsp
     * @return string
     */
    function str_replace_multiple_space(string $str, bool $withNbsp = false) : string
    {
        if ($withNbsp) {
            $str = str_replace('&nbsp;', ' ', $str);
        }
        return preg_replace('/\s+/', ' ', $str);
    }
}

if (!function_exists('str_replace_last')) {
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
}
if (!function_exists('segment')) {

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
}
if (!function_exists('firstSegment')) {

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
}

if (!function_exists('lastSegment')) {

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
}
/**
 * Return true if $subject is null or empty string ('').
 * @param $subject
 * @param bool $withTrim if set to true (default) check if trim()!='' too.
 * @return bool
 */
function isNullOrEmpty($subject, bool $withTrim = true) : bool
{
    return $subject === null || $subject == '' || ($withTrim === true && trim($subject) == '');
}

/**
 * Return true if $subject is not null and is not empty string ('').
 * @param $subject
 * @param bool $withTrim if set to true (default) check if trim()!='' too.
 * @return bool
 */
function isNotNullOrEmpty($subject, bool $withTrim = true) : bool
{
    return !isNullOrEmpty($subject, $withTrim);
}


/**
 * Convert number to word representation.
 *
 * @param int $number number to convert to word
 * @param string $locale default 'IT' support only IT or EN
 *
 * @return string converted string
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function numberToWord(int $number, string $locale = 'IT') : string
{
    if (isNullOrEmpty($locale) || (strtoupper($locale) != 'IT' && strtoupper($locale) != 'EN')) {
        $locale = 'IT';
    } else {
        $locale = strtoupper($locale);
    }

    $hyphen = $locale == 'EN' ? '-' : '';
    $conjunction = $locale == 'EN' ? ' and ' : ' ';
    $separator = ', ';
    $negative = $locale == 'EN' ? 'negative ' : 'negativo ';
    $decimal = $locale == 'EN' ? ' point ' : ' punto ';
    $fraction = null;
    $dictionary = $locale == 'EN' ? NUMBERS_EN_ARR : NUMBERS_ITA_ARR;
    if (!is_numeric($number)) {
        return '';
    }
    if (!isInteger($number, false, true)) {
        trigger_error('numberToWord only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING);
        return '';
    }
    if ($number < 0) {
        return $negative . numberToWord(abs($number), $locale);
    }
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numberToWord($remainder, $locale);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numberToWord($numBaseUnits, $locale) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= numberToWord($remainder, $locale);
            }
            break;
    }
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = [];
        foreach (str_split((string)$fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    return $string;
}

/**
 * Convert seconds to real time.
 *
 * @param int $seconds time in seconds
 * @param bool $returnAsWords return time in words (example one minute and 20 seconds) if value is True or (1 minute and 20 seconds) if value is false, default false
 * @param string $locale 'IT' default, or 'EN'
 *
 * @return string
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function secondsToText(int $seconds, bool $returnAsWords = false, string $locale = 'IT') : string
{
    $parts = [];
    $arrPeriod = ($locale == 'EN' ? PERIOD_IN_SECONDS_EN_ARR : PERIOD_IN_SECONDS_ITA_ARR);
    foreach ($arrPeriod as $name => $dur) {
        $div = floor($seconds / $dur);
        if ($div == 0) {
            continue;
        }
        if ($div == 1) {
            $parts[] = ($returnAsWords ? numberToWord($div, $locale) : $div) . ' ' . $name;
        } else {
            $parts[] = ($returnAsWords ? numberToWord($div,
                    $locale) : $div) . ' ' . ($locale == 'EN' ? $name : PERIOD_SINGULAR_PLURAL_ITA_ARR[$name]) . (strtoupper($locale) == 'EN' ? 's' : '');
        }
        $seconds %= $dur;
    }
    $last = array_pop($parts);
    if (isNullOrEmptyArray($parts)) {
        return $last;
    }
    return implode(', ', $parts) . (strtoupper($locale) == 'EN' ? ' and ' : ' e ') . $last;
}


/**
 * Convert minutes to real time.
 *
 * @param float $minutes time in minutes
 * @param bool $returnAsWords return time in words (example one hour and 20 minutes) if value is True or (1 hour and 20 minutes) if value is false, default false
 * @param string $locale 'IT' (default) or 'EN'
 *
 * @return string
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function minutesToText(float $minutes, bool $returnAsWords = false, string $locale = 'IT') : string
{
    $seconds = $minutes * 60;
    return secondsToText($seconds, $returnAsWords, $locale);
}

/**
 * Convert hours to real time.
 *
 * @param float $hours time in hours
 * @param bool $returnAsWords return time in words (example one hour) if value is True or (1 hour) if value is false, default false
 * @param string $locale 'IT' (default) or 'EN'
 *
 * @return string
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function hoursToText(float $hours, bool $returnAsWords = false, string $locale = 'IT') : string
{
    $seconds = $hours * 3600;
    return secondsToText($seconds, $returnAsWords, $locale);
}

if (!function_exists('str_html_compress')) {

    /**
     * Removes whitespace from html and compact it.
     * @param string $value
     * @return string
     */
    function str_html_compress(string $value) : string
    {
        return preg_replace(array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'), array('>', '<', '\\1'), $value);
    }
}
