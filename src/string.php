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
        if (isNullOrEmpty($haystack) || (!is_array($needles) && isNullOrEmpty($needles))) {
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
        if (isNullOrEmpty($haystack) || (!is_array($needles) && isNullOrEmpty($needles))) {
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
 * Return true if $subject is null or the string representation(cast to string) of $subject is an empty string ('').
 * @param $subject
 * @param bool $withTrim if set to true (default) and $subject is a scalar then check if trim($subject)!='' too.
 * @return bool
 */
function isNullOrEmpty($subject, bool $withTrim = true) : bool
{
    return $subject === null || $subject === '' || ($withTrim === true && is_scalar($subject) && trim($subject) == '');
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

if (!function_exists('str_word_count_utf8')) {

    /**
     * Count number of words in string.
     * Return zero if an error occourred.
     * @param string $str
     * @return int
     * @see https://github.com/ifsnop/lpsf/blob/master/src/Ifsnop/functions.inc.php
     */
    function str_word_count_utf8(string $str) : int
    {
        $result = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", $str, $matches);
        return $result === false ? 0 : $result;
    }
}

if (!function_exists('slugify')) {

    /**
     * Generate a URL friendly "slug" from a given string.
     * Converts the string into an URL slug. This includes replacing non-ASCII
     * characters with their closest ASCII equivalents, removing remaining
     * non-ASCII and non-alphanumeric characters, and replacing whitespace with
     * $replacement. The replacement defaults to a single dash, and the string
     * is also converted to lowercase.
     * @param string $title
     * @param string $separator
     * @return string
     * @see normalizeUtf8String in https://github.com/padosoft/support/blob/master/src/sanitize.php#L150
     * @see https://github.com/illuminate/support/blob/master/Str.php
     */
    function slugify(string $title, string $separator = '-') : string
    {
        //removes all diacritics (marks like accents) from a given UTF8-encoded
        //texts and returns ASCii-text equivalent(if possible).
        $title = normalizeUtf8String($title);

        // Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);

        return trim($title, $separator);
    }
}

if (!function_exists('charsArray')) {
    /**
     * Returns the replacements for the non-Ascii chars.
     *
     * @return array An array of replacements.
     * @see https://github.com/danielstjules/Stringy/blob/master/src/Stringy.php
     */
    function charsArray()
    {
        return array(
            '0' => array('°', '₀', '۰'),
            '1' => array('¹', '₁', '۱'),
            '2' => array('²', '₂', '۲'),
            '3' => array('³', '₃', '۳'),
            '4' => array('⁴', '₄', '۴', '٤'),
            '5' => array('⁵', '₅', '۵', '٥'),
            '6' => array('⁶', '₆', '۶', '٦'),
            '7' => array('⁷', '₇', '۷'),
            '8' => array('⁸', '₈', '۸'),
            '9' => array('⁹', '₉', '۹'),
            'a' => array(
                'à',
                'á',
                'ả',
                'ã',
                'ạ',
                'ă',
                'ắ',
                'ằ',
                'ẳ',
                'ẵ',
                'ặ',
                'â',
                'ấ',
                'ầ',
                'ẩ',
                'ẫ',
                'ậ',
                'ā',
                'ą',
                'å',
                'α',
                'ά',
                'ἀ',
                'ἁ',
                'ἂ',
                'ἃ',
                'ἄ',
                'ἅ',
                'ἆ',
                'ἇ',
                'ᾀ',
                'ᾁ',
                'ᾂ',
                'ᾃ',
                'ᾄ',
                'ᾅ',
                'ᾆ',
                'ᾇ',
                'ὰ',
                'ά',
                'ᾰ',
                'ᾱ',
                'ᾲ',
                'ᾳ',
                'ᾴ',
                'ᾶ',
                'ᾷ',
                'а',
                'أ',
                'အ',
                'ာ',
                'ါ',
                'ǻ',
                'ǎ',
                'ª',
                'ა',
                'अ',
                'ا'
            ),
            'b' => array('б', 'β', 'Ъ', 'Ь', 'ب', 'ဗ', 'ბ'),
            'c' => array('ç', 'ć', 'č', 'ĉ', 'ċ'),
            'd' => array(
                'ď',
                'ð',
                'đ',
                'ƌ',
                'ȡ',
                'ɖ',
                'ɗ',
                'ᵭ',
                'ᶁ',
                'ᶑ',
                'д',
                'δ',
                'د',
                'ض',
                'ဍ',
                'ဒ',
                'დ'
            ),
            'e' => array(
                'é',
                'è',
                'ẻ',
                'ẽ',
                'ẹ',
                'ê',
                'ế',
                'ề',
                'ể',
                'ễ',
                'ệ',
                'ë',
                'ē',
                'ę',
                'ě',
                'ĕ',
                'ė',
                'ε',
                'έ',
                'ἐ',
                'ἑ',
                'ἒ',
                'ἓ',
                'ἔ',
                'ἕ',
                'ὲ',
                'έ',
                'е',
                'ё',
                'э',
                'є',
                'ə',
                'ဧ',
                'ေ',
                'ဲ',
                'ე',
                'ए',
                'إ',
                'ئ'
            ),
            'f' => array('ф', 'φ', 'ف', 'ƒ', 'ფ'),
            'g' => array('ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ'),
            'h' => array('ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ'),
            'i' => array(
                'í',
                'ì',
                'ỉ',
                'ĩ',
                'ị',
                'î',
                'ï',
                'ī',
                'ĭ',
                'į',
                'ı',
                'ι',
                'ί',
                'ϊ',
                'ΐ',
                'ἰ',
                'ἱ',
                'ἲ',
                'ἳ',
                'ἴ',
                'ἵ',
                'ἶ',
                'ἷ',
                'ὶ',
                'ί',
                'ῐ',
                'ῑ',
                'ῒ',
                'ΐ',
                'ῖ',
                'ῗ',
                'і',
                'ї',
                'и',
                'ဣ',
                'ိ',
                'ီ',
                'ည်',
                'ǐ',
                'ი',
                'इ',
                'ی'
            ),
            'j' => array('ĵ', 'ј', 'Ј', 'ჯ', 'ج'),
            'k' => array(
                'ķ',
                'ĸ',
                'к',
                'κ',
                'Ķ',
                'ق',
                'ك',
                'က',
                'კ',
                'ქ',
                'ک'
            ),
            'l' => array('ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ'),
            'm' => array('м', 'μ', 'م', 'မ', 'მ'),
            'n' => array(
                'ñ',
                'ń',
                'ň',
                'ņ',
                'ŉ',
                'ŋ',
                'ν',
                'н',
                'ن',
                'န',
                'ნ'
            ),
            'o' => array(
                'ó',
                'ò',
                'ỏ',
                'õ',
                'ọ',
                'ô',
                'ố',
                'ồ',
                'ổ',
                'ỗ',
                'ộ',
                'ơ',
                'ớ',
                'ờ',
                'ở',
                'ỡ',
                'ợ',
                'ø',
                'ō',
                'ő',
                'ŏ',
                'ο',
                'ὀ',
                'ὁ',
                'ὂ',
                'ὃ',
                'ὄ',
                'ὅ',
                'ὸ',
                'ό',
                'о',
                'و',
                'θ',
                'ို',
                'ǒ',
                'ǿ',
                'º',
                'ო',
                'ओ'
            ),
            'p' => array('п', 'π', 'ပ', 'პ', 'پ'),
            'q' => array('ყ'),
            'r' => array('ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ'),
            's' => array(
                'ś',
                'š',
                'ş',
                'с',
                'σ',
                'ș',
                'ς',
                'س',
                'ص',
                'စ',
                'ſ',
                'ს'
            ),
            't' => array(
                'ť',
                'ţ',
                'т',
                'τ',
                'ț',
                'ت',
                'ط',
                'ဋ',
                'တ',
                'ŧ',
                'თ',
                'ტ'
            ),
            'u' => array(
                'ú',
                'ù',
                'ủ',
                'ũ',
                'ụ',
                'ư',
                'ứ',
                'ừ',
                'ử',
                'ữ',
                'ự',
                'û',
                'ū',
                'ů',
                'ű',
                'ŭ',
                'ų',
                'µ',
                'у',
                'ဉ',
                'ု',
                'ူ',
                'ǔ',
                'ǖ',
                'ǘ',
                'ǚ',
                'ǜ',
                'უ',
                'उ'
            ),
            'v' => array('в', 'ვ', 'ϐ'),
            'w' => array('ŵ', 'ω', 'ώ', 'ဝ', 'ွ'),
            'x' => array('χ', 'ξ'),
            'y' => array(
                'ý',
                'ỳ',
                'ỷ',
                'ỹ',
                'ỵ',
                'ÿ',
                'ŷ',
                'й',
                'ы',
                'υ',
                'ϋ',
                'ύ',
                'ΰ',
                'ي',
                'ယ'
            ),
            'z' => array('ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ'),
            'aa' => array('ع', 'आ', 'آ'),
            'ae' => array('ä', 'æ', 'ǽ'),
            'ai' => array('ऐ'),
            'at' => array('@'),
            'ch' => array('ч', 'ჩ', 'ჭ', 'چ'),
            'dj' => array('ђ', 'đ'),
            'dz' => array('џ', 'ძ'),
            'ei' => array('ऍ'),
            'gh' => array('غ', 'ღ'),
            'ii' => array('ई'),
            'ij' => array('ĳ'),
            'kh' => array('х', 'خ', 'ხ'),
            'lj' => array('љ'),
            'nj' => array('њ'),
            'oe' => array('ö', 'œ', 'ؤ'),
            'oi' => array('ऑ'),
            'oii' => array('ऒ'),
            'ps' => array('ψ'),
            'sh' => array('ш', 'შ', 'ش'),
            'shch' => array('щ'),
            'ss' => array('ß'),
            'sx' => array('ŝ'),
            'th' => array('þ', 'ϑ', 'ث', 'ذ', 'ظ'),
            'ts' => array('ц', 'ც', 'წ'),
            'ue' => array('ü'),
            'uu' => array('ऊ'),
            'ya' => array('я'),
            'yu' => array('ю'),
            'zh' => array('ж', 'ჟ', 'ژ'),
            '(c)' => array('©'),
            'A' => array(
                'Á',
                'À',
                'Ả',
                'Ã',
                'Ạ',
                'Ă',
                'Ắ',
                'Ằ',
                'Ẳ',
                'Ẵ',
                'Ặ',
                'Â',
                'Ấ',
                'Ầ',
                'Ẩ',
                'Ẫ',
                'Ậ',
                'Å',
                'Ā',
                'Ą',
                'Α',
                'Ά',
                'Ἀ',
                'Ἁ',
                'Ἂ',
                'Ἃ',
                'Ἄ',
                'Ἅ',
                'Ἆ',
                'Ἇ',
                'ᾈ',
                'ᾉ',
                'ᾊ',
                'ᾋ',
                'ᾌ',
                'ᾍ',
                'ᾎ',
                'ᾏ',
                'Ᾰ',
                'Ᾱ',
                'Ὰ',
                'Ά',
                'ᾼ',
                'А',
                'Ǻ',
                'Ǎ'
            ),
            'B' => array('Б', 'Β', 'ब'),
            'C' => array('Ç', 'Ć', 'Č', 'Ĉ', 'Ċ'),
            'D' => array('Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ'),
            'E' => array(
                'É',
                'È',
                'Ẻ',
                'Ẽ',
                'Ẹ',
                'Ê',
                'Ế',
                'Ề',
                'Ể',
                'Ễ',
                'Ệ',
                'Ë',
                'Ē',
                'Ę',
                'Ě',
                'Ĕ',
                'Ė',
                'Ε',
                'Έ',
                'Ἐ',
                'Ἑ',
                'Ἒ',
                'Ἓ',
                'Ἔ',
                'Ἕ',
                'Έ',
                'Ὲ',
                'Е',
                'Ё',
                'Э',
                'Є',
                'Ə'
            ),
            'F' => array('Ф', 'Φ'),
            'G' => array('Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ'),
            'H' => array('Η', 'Ή', 'Ħ'),
            'I' => array(
                'Í',
                'Ì',
                'Ỉ',
                'Ĩ',
                'Ị',
                'Î',
                'Ï',
                'Ī',
                'Ĭ',
                'Į',
                'İ',
                'Ι',
                'Ί',
                'Ϊ',
                'Ἰ',
                'Ἱ',
                'Ἳ',
                'Ἴ',
                'Ἵ',
                'Ἶ',
                'Ἷ',
                'Ῐ',
                'Ῑ',
                'Ὶ',
                'Ί',
                'И',
                'І',
                'Ї',
                'Ǐ',
                'ϒ'
            ),
            'K' => array('К', 'Κ'),
            'L' => array('Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल'),
            'M' => array('М', 'Μ'),
            'N' => array('Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν'),
            'O' => array(
                'Ó',
                'Ò',
                'Ỏ',
                'Õ',
                'Ọ',
                'Ô',
                'Ố',
                'Ồ',
                'Ổ',
                'Ỗ',
                'Ộ',
                'Ơ',
                'Ớ',
                'Ờ',
                'Ở',
                'Ỡ',
                'Ợ',
                'Ø',
                'Ō',
                'Ő',
                'Ŏ',
                'Ο',
                'Ό',
                'Ὀ',
                'Ὁ',
                'Ὂ',
                'Ὃ',
                'Ὄ',
                'Ὅ',
                'Ὸ',
                'Ό',
                'О',
                'Θ',
                'Ө',
                'Ǒ',
                'Ǿ'
            ),
            'P' => array('П', 'Π'),
            'R' => array('Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ'),
            'S' => array('Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ'),
            'T' => array('Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ'),
            'U' => array(
                'Ú',
                'Ù',
                'Ủ',
                'Ũ',
                'Ụ',
                'Ư',
                'Ứ',
                'Ừ',
                'Ử',
                'Ữ',
                'Ự',
                'Û',
                'Ū',
                'Ů',
                'Ű',
                'Ŭ',
                'Ų',
                'У',
                'Ǔ',
                'Ǖ',
                'Ǘ',
                'Ǚ',
                'Ǜ'
            ),
            'V' => array('В'),
            'W' => array('Ω', 'Ώ', 'Ŵ'),
            'X' => array('Χ', 'Ξ'),
            'Y' => array(
                'Ý',
                'Ỳ',
                'Ỷ',
                'Ỹ',
                'Ỵ',
                'Ÿ',
                'Ῠ',
                'Ῡ',
                'Ὺ',
                'Ύ',
                'Ы',
                'Й',
                'Υ',
                'Ϋ',
                'Ŷ'
            ),
            'Z' => array('Ź', 'Ž', 'Ż', 'З', 'Ζ'),
            'AE' => array('Ä', 'Æ', 'Ǽ'),
            'CH' => array('Ч'),
            'DJ' => array('Ђ'),
            'DZ' => array('Џ'),
            'GX' => array('Ĝ'),
            'HX' => array('Ĥ'),
            'IJ' => array('Ĳ'),
            'JX' => array('Ĵ'),
            'KH' => array('Х'),
            'LJ' => array('Љ'),
            'NJ' => array('Њ'),
            'OE' => array('Ö', 'Œ'),
            'PS' => array('Ψ'),
            'SH' => array('Ш'),
            'SHCH' => array('Щ'),
            'SS' => array('ẞ'),
            'TH' => array('Þ'),
            'TS' => array('Ц'),
            'UE' => array('Ü'),
            'YA' => array('Я'),
            'YU' => array('Ю'),
            'ZH' => array('Ж'),
            ' ' => array(
                "\xC2\xA0",
                "\xE2\x80\x80",
                "\xE2\x80\x81",
                "\xE2\x80\x82",
                "\xE2\x80\x83",
                "\xE2\x80\x84",
                "\xE2\x80\x85",
                "\xE2\x80\x86",
                "\xE2\x80\x87",
                "\xE2\x80\x88",
                "\xE2\x80\x89",
                "\xE2\x80\x8A",
                "\xE2\x80\xAF",
                "\xE2\x81\x9F",
                "\xE3\x80\x80"
            ),
        );
    }
}
if (!function_exists('charsArrayRegEx')) {
    /**
     * Returns regex to replacements for diacritics, German chars, and non ASCII chars.
     *
     * @return array An array of regex.
     */
    function charsArrayRegEx()
    {
        return array(
            // maps German (umlauts) and other European characters onto two characters
            // before just removing diacritics
            "AE" => '/\x{00c4}/u', // umlaut Ä => AE
            "OE" => '/\x{00d6}/u', // umlaut Ö => OE
            "UE" => '/\x{00dc}/u', // umlaut Ü => UE
            "ae" => '/\x{00e4}/u', // umlaut ä => ae
            "oe" => '/\x{00f6}/u', // umlaut ö => oe
            "ue" => '/\x{00fc}/u', // umlaut ü => ue
            "ny" => '/\x{00f1}/u', // ñ => ny
            "yu" => '/\x{00ff}/u', // ÿ => yu

            "" => '/\pM/u', // removes diacritics

            "ss" => '/\x{00df}/u', // maps German ß onto ss
            "AE" => '/\x{00c6}/u', // Æ => AE
            "ae" => '/\x{00e6}/u', // æ => ae
            "IJ" => '/\x{0132}/u', // ? => IJ
            "ij" => '/\x{0133}/u', // ? => ij
            "OE" => '/\x{0152}/u', // Œ => OE
            "oe" => '/\x{0153}/u', // œ => oe

            "D" => '/\x{00d0}/u', // Ð => D
            "D" => '/\x{0110}/u', // Ð => D
            "d" => '/\x{00f0}/u', // ð => d
            "d" => '/\x{0111}/u', // d => d
            "H" => '/\x{0126}/u', // H => H
            "h" => '/\x{0127}/u', // h => h
            "i" => '/\x{0131}/u', // i => i
            "k" => '/\x{0138}/u', // ? => k
            "L" => '/\x{013f}/u', // ? => L
            "L" => '/\x{0141}/u', // L => L
            "l" => '/\x{0140}/u', // ? => l
            "l" => '/\x{0142}/u', // l => l
            "N" => '/\x{014a}/u', // ? => N
            "n" => '/\x{0149}/u', // ? => n
            "n" => '/\x{014b}/u', // ? => n
            "O" => '/\x{00d8}/u', // Ø => O
            "o" => '/\x{00f8}/u', // ø => o
            "s" => '/\x{017f}/u', // ? => s
            "T" => '/\x{00de}/u', // Þ => T
            "T" => '/\x{0166}/u', // T => T
            "t" => '/\x{00fe}/u', // þ => t
            "t" => '/\x{0167}/u', // t => t

            '' => '/[^\x20-\x7E]/u', // remove all non-ASCii characters
            '' => '/[^\0-\x80]/u', // remove all non-ASCii characters
        );
    }
}
