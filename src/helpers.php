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

/**
 * Convert the output of PHP's filesize() function
 * to a nice format with PB, TB, GB, MB, kB, bytes.
 * @param $bytes
 * @return string
 */
function bytes2HumanSize($bytes)
{
    if ($bytes >= 1125899906842624) {
        $bytes = number_format($bytes / 1073741824, 2) . ' PB';
    } elseif ($bytes >= 1099511627776) {
        $bytes = number_format($bytes / 1073741824, 2) . ' TB';
    } elseif ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' kB';
    } elseif ($bytes > 1) {
        $bytes .= ' bytes';
    } elseif ($bytes == 1) {
        $bytes .= ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

/**
 * This function transforms the php.ini notation for numbers (like '2M')
 * to an integer (2*1024*1024 in this case)
 * @param $sSize
 * @return int|string
 */
function convertPHPSizeToBytes($sSize)
{
    if (is_numeric($sSize)) {
        return $sSize;
    }
    $sSuffix = substr($sSize, -1);
    $iValue = substr($sSize, 0, -1);

    switch (strtoupper($sSuffix)) {
        case 'P':
            $iValue *= 1024;
        case 'T':
            $iValue *= 1024;
        case 'G':
            $iValue *= 1024;
        case 'M':
            $iValue *= 1024;
        case 'K':
            $iValue *= 1024;
            break;
    }
    return $iValue;
}

/**
 * Return the Max upload size in bytes.
 * @param bool $humanFormat if set to true return size in human format (MB, kB, etc..) otherwise return in bytes.
 * @return int
 */
function getMaximumFileUploadSize(bool $humanFormat = false)
{
    $size = min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));

    if (!$humanFormat) {
        return $size;
    }

    return bytes2HumanSize($size);
}

/**
 * Encrypt string.
 * @param string $string to encrypt.
 * @param string $chiave the key to encrypt. if empty generate a random key on the fly.
 * @return string
 */
function EncryptString(string $string, string $chiave = '')
{
    if ($chiave == '') {
        $chiave = str_random(64);
    }

    $key = pack('H*', $chiave);

    $plaintext = $string;

    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    # creates a cipher text compatible with AES (Rijndael block size = 128)
    # to keep the text confidential
    # only suitable for encoded input that never ends with value 00h
    # (because of default zero padding)
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);

    # prepend the IV for it to be available for decryption
    $ciphertext = $iv . $ciphertext;

    # encode the resulting cipher text so it can be represented by a string
    $ciphertext_base64 = base64_encode($ciphertext);

    return $ciphertext_base64;
}
