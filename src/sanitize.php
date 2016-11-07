<?php

if (!function_exists('strip_nl')) {

    /**
     * Strip new line breaks from a string
     * @param $str
     * @return string|array
     */
    function strip_nl($str)
    {
        return str_replace("\n", "", str_replace("\r", "", $str));
    }
}

if (!function_exists('jse')) {

    /**
     * Javascript escape
     * @param string $str
     * @return string
     * @source https://github.com/rtconner/laravel-plusplus/blob/laravel-5/src/plus-functions.php
     */
    function jse(string $str) : string
    {
        if (isNullOrEmpty($str)) {
            return '';
        }
        $str = str_replace("\n", "", str_replace("\r", "", $str));
        return addslashes($str);
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML entities in a string.
     *
     * @param  string $value
     * @return string
     */
    function e($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('csse')) {
    /**
     * Escape CSS entities in a string.
     *
     * @param  string $value
     * @return string
     * @see https://github.com/auraphp/Aura.Html/blob/2.x/src/Escaper/CssEscaper.php
     */
    function csse($value)
    {
        // pre-empt replacement
        if ($value === '' || ctype_digit($value)) {
            return $value;
        }
        return preg_replace_callback(
            '/[^a-z0-9]/iSu',
            function ($matches) {
                // get the character
                $chr = $matches[0];
                // is it UTF-8?
                if (strlen($chr) == 1) {
                    // yes
                    $ord = ord($chr);
                    return sprintf('\\%X ', $ord);
                }
            },
            $value
        );
    }
}

if (!function_exists('attre')) {
    /**
     * Escape HTML Attribute entities in a string.
     *
     * @param  string $value
     * @return string
     * @see https://github.com/auraphp/Aura.Html/blob/2.x/src/Escaper/AttrEscaper.php
     */
    function attre($value)
    {
        // pre-empt replacement
        if ($value === '' || ctype_digit($value)) {
            return $value;
        }
        return preg_replace_callback(
            '/[^a-z0-9,\.\-_]/iSu',
            function ($matches) {
                $chr = $matches[0];
                $ord = ord($chr);
                if (($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r")
                    || ($ord >= 0x7f && $ord <= 0x9f)
                ) {
                    // use the Unicode replacement char
                    return '&#xFFFD;';
                }
                $entities = array(
                    34 => '&quot;',
                    38 => '&amp;',
                    60 => '&lt;',
                    62 => '&gt;',
                );
                // is this a mapped entity?
                if (isset($entities[$ord])) {
                    return $entities[$ord];
                }
                // is this an upper-range hex entity?
                if ($ord > 255) {
                    return sprintf('&#x%04X;', $ord);
                }
                // everything else
                return sprintf('&#x%02X;', $ord);
            },
            $value
        );
    }
}

if (!function_exists('she')) {

    /**
     * Escape Shell argument.
     * @param string $input
     * @return string
     */
    function she(string $input) : string
    {
        if (windows_os()) {
            return '"' . addcslashes($input, '\\"') . '"';
        }

        return escapeshellarg($input);
    }
}

/**
 * Normalize the string.
 * The following function removes all diacritics (marks like accents) from a given UTF8-encoded
 * texts and returns ASCii-text.
 * @param string $s
 * @return string
 * @see https://github.com/illuminate/support/blob/master/Str.php#L38
 * @see http://php.net/manual/en/normalizer.normalize.php#92592
 */
function normalizeUtf8String(string $s) : string
{
    $original_string = $s;

    //Transliterate UTF-8 value to ASCII with chars array map.
    $charsArray = charsArray();
    array_walk($charsArray, function($val,$key) use (&$s){
        $s = str_replace($val, $key, $s);
    });

    //replace non ASCII chars with array regex map.
    $charsRegExArray = charsArrayRegEx();
    array_walk($charsRegExArray, function($val,$key) use (&$s){
        $s = preg_replace($val, $key, $s);
    });

    // Normalize utf8 in form D
    // if exists use Normalizer-class to maps remaining special characters
    // (characters with diacritics) on their base-character followed by the diacritical mark
    // exmaple:  Ú => U´,  á => a`
    $s = normalizerUtf8Safe($s, Normalizer::FORM_D);

    // possible errors in UTF8-regular-expressions
    if (isNullOrEmpty($s)) {
        return $original_string;
    }

    return $s;
}

/**
 * Normalize uft8 to various form with php normalizer function if exists,
 * otherwise return original string.
 * maps special characters (characters with diacritics) on their base-character
 * followed by the diacritical mark
 * exmaple:  Ú => U´,  á => a`
 * @param string $s
 * @param $normalizationForm UTF8 Normalization Form if empty Default Normalizer::FORM_D
 * @return string
 */
function normalizerUtf8Safe(string $s, $normalizationForm):string
{
    if (class_exists("Normalizer", false)) {
        $s = Normalizer::normalize($s, isNullOrEmpty($normalizationForm) ? Normalizer::FORM_D : $normalizationForm);
        return $s;
    }
    return $s;
}

/**
 * String Sanitizer for Filename
 * @param string $fileName
 * @param bool $sanitizeForPath if set to false (default) sanitize file name, otherwise file path name
 * @param string $charToReplaceWhiteSpace if empty (default) or ' ' then white space ' ' will be preservede
 * othrwise it will be replaced with $charToReplaceWhiteSpace.
 * @return string
 * @see for base script idea http://stackoverflow.com/a/2021729
 */
function sanitize_filename(
    string $fileName,
    bool $sanitizeForPath = false,
    string $charToReplaceWhiteSpace = ' '
) : string
{
    //check whitespace
    $fileName = str_replace(' ', $charToReplaceWhiteSpace, $fileName);

    // Remove any runs of periods - avoid Path Traversal Vulnerabilities OSWAP
    // https://www.owasp.org/index.php/Path_Traversal
    $notAllowedPath = [
        '//',
        '\\\\',
        '../',
        './',
        '..\\',
        '.\\',
        '%2e%2e%2f',
        '%2e%2e/',
        '..%2f',
        '%2e%2e%5c',
        '%2e%2e\\',
        '..%5c',
        '%252e%252e%255c',
        '..%255c',
        '..%c0%af',
        '..%c1%9c',
    ];
    while (str_contains($fileName, $notAllowedPath) !== false) {
        $fileName = str_replace($notAllowedPath, '', $fileName);
    }

    // Remove anything which isn't a word, whitespace, number
    // or any of the following caracters -_~,;[]().
    // If you don't need to handle multi-byte characters
    // you can use preg_replace rather than mb_ereg_replace
    // Thanks @Łukasz Rysiak!
    $fileName = mb_ereg_replace('([^\w\s\d\-_~,;\[\]\(\).' . ($sanitizeForPath ? '\\/' : '') . '])', '', $fileName);

    // remove exadecimal, non white space chars
    $fileName = mb_ereg_replace('([[:cntrl:]\b\0\n\r\t\f])', '', $fileName);

    //normalize and trim
    $fileName = trim(normalizeUtf8String($fileName));

    //do not start with ..
    while (starts_with($fileName, '..') !== false) {
        $fileName = substr($fileName, 2);
    }

    //do not end with ..
    while (ends_with($fileName, '..') !== false) {
        $fileName = substr($fileName, 0, -2);
    }
    //do not end with .
    while (ends_with($fileName, '.') !== false) {
        $fileName = substr($fileName, 0, -1);
    }

    return $fileName;
}

/**
 * String Sanitizer for Path name
 * @param string $pathName
 * @param string $charToReplaceWhiteSpace if empty (default) or ' ' then white space ' ' will be preservede
 * othrwise it will be replaced with $charToReplaceWhiteSpace.
 * @return string
 */

function sanitize_pathname(string $pathName, string $charToReplaceWhiteSpace) : string
{
    return sanitize_filename($pathName, true, $charToReplaceWhiteSpace);
}

/**
 * Perform XSS clean to prevent cross site scripting.
 *
 * @param array $data
 *
 * @return array
 */
function sanitize_arr_string_xss(array $data) : array
{
    foreach ($data as $k => $v) {
        $data[$k] = filter_var($v, FILTER_SANITIZE_STRING);
    }
    return $data;
}

/**
 * Perform XSS clean to prevent cross site scripting.
 *
 * @param string $data
 *
 * @return string
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function sanitize_string_xss(string $data) : string
{
    return filter_var($data, FILTER_SANITIZE_STRING);
}

/**
 * Sanitize the string by urlencoding characters.
 *
 * @param string $value
 *
 * @return string
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function sanitize_urlencode($value)
{
    return filter_var($value, FILTER_SANITIZE_ENCODED);
}

/**
 * Sanitize the string by removing illegal characters from emails.
 *
 * @param string $value
 *
 * @return string
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function sanitize_email($value)
{
    return filter_var($value, FILTER_SANITIZE_EMAIL);
}

/**
 * Sanitize the string by removing illegal characters from numbers.
 *
 * @param string $value
 *
 * @return string
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function sanitize_numbers($value)
{
    return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Sanitize the string by removing illegal characters from float numbers.
 *
 * @param string $value
 *
 * @return string
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function sanitize_floats($value)
{
    return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}
