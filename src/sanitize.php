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
 * Normalize the texts before.
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
    foreach (charsArray() as $key => $val) {
        $s = str_replace($val, $key, $s);
    }
    $s = preg_replace('/[^\x20-\x7E]/u', '', $s);

    // maps German (umlauts) and other European characters onto two characters before just removing diacritics
    $s = preg_replace('/\x{00c4}/u', "AE", $s);    // umlaut Ä => AE
    $s = preg_replace('/\x{00d6}/u', "OE", $s);    // umlaut Ö => OE
    $s = preg_replace('/\x{00dc}/u', "UE", $s);    // umlaut Ü => UE
    $s = preg_replace('/\x{00e4}/u', "ae", $s);    // umlaut ä => ae
    $s = preg_replace('/\x{00f6}/u', "oe", $s);    // umlaut ö => oe
    $s = preg_replace('/\x{00fc}/u', "ue", $s);    // umlaut ü => ue
    $s = preg_replace('/\x{00f1}/u', "ny", $s);    // ñ => ny
    $s = preg_replace('/\x{00ff}/u', "yu", $s);    // ÿ => yu

    // if exists use Normalizer-class
    if (class_exists("Normalizer", false)) {
        // maps special characters (characters with diacritics) on their base-character followed by the diacritical mark
        // exmaple:  Ú => U´,  á => a`
        $s = Normalizer::normalize($s, Normalizer::FORM_D);
    }

    $s = preg_replace('/\pM/u', "", $s);    // removes diacritics

    $s = preg_replace('/\x{00df}/u', "ss", $s);    // maps German ß onto ss
    $s = preg_replace('/\x{00c6}/u', "AE", $s);    // Æ => AE
    $s = preg_replace('/\x{00e6}/u', "ae", $s);    // æ => ae
    $s = preg_replace('/\x{0132}/u', "IJ", $s);    // ? => IJ
    $s = preg_replace('/\x{0133}/u', "ij", $s);    // ? => ij
    $s = preg_replace('/\x{0152}/u', "OE", $s);    // Œ => OE
    $s = preg_replace('/\x{0153}/u', "oe", $s);    // œ => oe

    $s = preg_replace('/\x{00d0}/u', "D", $s);    // Ð => D
    $s = preg_replace('/\x{0110}/u', "D", $s);    // Ð => D
    $s = preg_replace('/\x{00f0}/u', "d", $s);    // ð => d
    $s = preg_replace('/\x{0111}/u', "d", $s);    // d => d
    $s = preg_replace('/\x{0126}/u', "H", $s);    // H => H
    $s = preg_replace('/\x{0127}/u', "h", $s);    // h => h
    $s = preg_replace('/\x{0131}/u', "i", $s);    // i => i
    $s = preg_replace('/\x{0138}/u', "k", $s);    // ? => k
    $s = preg_replace('/\x{013f}/u', "L", $s);    // ? => L
    $s = preg_replace('/\x{0141}/u', "L", $s);    // L => L
    $s = preg_replace('/\x{0140}/u', "l", $s);    // ? => l
    $s = preg_replace('/\x{0142}/u', "l", $s);    // l => l
    $s = preg_replace('/\x{014a}/u', "N", $s);    // ? => N
    $s = preg_replace('/\x{0149}/u', "n", $s);    // ? => n
    $s = preg_replace('/\x{014b}/u', "n", $s);    // ? => n
    $s = preg_replace('/\x{00d8}/u', "O", $s);    // Ø => O
    $s = preg_replace('/\x{00f8}/u', "o", $s);    // ø => o
    $s = preg_replace('/\x{017f}/u', "s", $s);    // ? => s
    $s = preg_replace('/\x{00de}/u', "T", $s);    // Þ => T
    $s = preg_replace('/\x{0166}/u', "T", $s);    // T => T
    $s = preg_replace('/\x{00fe}/u', "t", $s);    // þ => t
    $s = preg_replace('/\x{0167}/u', "t", $s);    // t => t

    // remove all non-ASCii characters
    $s = preg_replace('/[^\0-\x80]/u', "", $s);

    // possible errors in UTF8-regular-expressions
    if (isNullOrEmpty($s)) {
        return $original_string;
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
