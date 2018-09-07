<?php

if (!function_exists('rgb2hex')) {

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return string
     * @see https://gist.github.com/Pushplaybang/5432844
     */
    function rgb2hex(int $red, int $green, int $blue):  string
    {
        $hex = "#";
        $hex .= str_pad(dechex($red), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($green), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($blue), 2, "0", STR_PAD_LEFT);

        return $hex; // returns the hex value including the number sign (#)
    }
}

if (!function_exists('hex2rgb')) {

    /**
     * Takes HEX color code value and converts to a RGB value.
     *
     * @param string $color Color hex value, example: #000000, #000 or 000000, 000
     *
     * @return string color rbd value
     */
    function hex2rgb($color)
    {
        $color = str_replace('#', '', $color);
        if (strlen($color) == 3):
            list($r, $g, $b) = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
        else:
            list($r, $g, $b) = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
        endif;
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return 'rgb(' . $r . ', ' . $g . ', ' . $b . ')';
    }
}

/**
 * @param float $val
 * @param int $precision
 * @param string $simbol
 * @param bool $prepend if true (default) prefix with $simbol, otherwise suffix with $simbol.
 * @return string
 */
function format_money(float $val = 0.00, int $precision = 2, string $simbol = "", bool $prepend=true) : string
{
    $prefix = $simbol.' ';
    $suffix = '';
    if(!$prepend){
        $prefix = '';
        $suffix = ' '.$simbol;
    }
    return trim($prefix . number_format($val, $precision, ',', '.').$suffix);
}

/**
 * Format float 1125.86 into string '&euro 1.125,86'
 * @param float $val
 * @param bool $prepend if true (default) prefix with '&euro; ', otherwise suffix with ' &euro;'.
 * @return string
 */
function format_euro(float $val = 0.00, bool $prepend=true) : string
{
    return format_money($val, 2, '&euro; ', $prepend);
}

if (!function_exists('ordinal')) {

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
 * @param int $decimals [optional] default 0. Sets the number of decimal points.
 * @return string
 */
function bytes2HumanSize($bytes, $decimals=0)
{
    if(!isIntegerPositiveOrZero($decimals)){
        $decimals = 0;
    }

    if ($bytes >= 1125899906842624) {
        $bytes = number_format($bytes / 1073741824, $decimals) . ' PB';
    } elseif ($bytes >= 1099511627776) {
        $bytes = number_format($bytes / 1073741824, $decimals) . ' TB';
    } elseif ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, $decimals) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, $decimals) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, $decimals) . ' kB';
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
 * @param string $sSize
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
        //PB multiplier
        case 'T':
            $iValue *= 1024;
        //TB multiplier
        case 'G':
            $iValue *= 1024;
        //GB multiplier
        case 'M':
            $iValue *= 1024;
        //MB multiplier
        case 'K':
            $iValue *= 1024;
            //KB multiplier
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
 * Encrypt string in asymmetrical way.
 * @param string $string to encrypt.
 * @param string $chiave the key to encrypt. if empty generate a random key on the fly.
 * @return string
 */
function encryptString(string $string, string $chiave = '')
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

/**
 * Get a Website favicon url.
 *
 * @param string $url website url
 *
 * @return string containing complete image tag
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function getFaviconUrl($url) : string
{
    $apiUrl = 'https://www.google.com/s2/favicons?domain=';
    if (strpos($url, 'http') !== false) {
        $url = str_replace('http://', '', $url);
    }
    return $apiUrl . $url;
}

/**
 * Get a Website favicon image tag.
 *
 * @param string $url website url
 * @param array $attributes Optional, additional key/value attributes to include in the IMG tag
 *
 * @return string containing complete image tag
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function getFaviconImgTag($url, array $attributes = []) : string
{
    $urlFav = getFaviconUrl($url);
    $attr = arrayToString($attributes);
    return '<img src="' . $urlFav . '" ' . trim($attr) . ' />';
}

/**
 * Check to see if the current page is being server over SSL or not.
 * Support HTTP_X_FORWARDED_PROTO to check ssl over proxy/load balancer.
 *
 * @return bool
 */
function isHttps()
{
    $key = 'HTTPS';
    if (isNotNullOrEmptyArrayKey($_SERVER, 'HTTP_X_FORWARDED_PROTO')) {
        $key = 'HTTP_X_FORWARDED_PROTO';
    }
    return isNotNullOrEmptyArrayKey($_SERVER, $key) && strtolower($_SERVER[$key]) !== 'off';
}

/**
 * Get a QR code.
 *
 * @param string $string String to generate QR code for.
 * @param int $width QR code width
 * @param int $height QR code height
 * @param array $attributes Optional, additional key/value attributes to include in the IMG tag
 *
 * @return string containing complete image tag
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function getQRcode($string, int $width = 150, int $height = 150, array $attributes = []) : string
{
    $attr = arrayToString($attributes);
    $apiUrl = getQRcodeUrl($string, $width, $height);
    return '<img src="' . $apiUrl . '" ' . trim($attr) . ' />';
}

/**
 * Get a QR code Url.
 *
 * @param string $string String to generate QR code for.
 * @param int $width QR code width
 * @param int $height QR code height
 *
 * @return string containing complete image url
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function getQRcodeUrl($string, int $width = 150, int $height = 150) : string
{
    $protocol = 'http://';
    if (isHttps()) {
        $protocol = 'https://';
    }
    return $protocol . 'chart.apis.google.com/chart?chs=' . $width . 'x' . $height . '&cht=qr&chl=' . urlencode($string);
}

if (!function_exists('gravatarUrl')) {
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
    function gravatarUrl($email, $size = 80, $default = 'mm', $rating = 'g')
    {
        $url = 'https://www.gravatar.com';
        $url .= '/avatar/' . md5(strtolower(trim($email))) . '?default=' . $default . '&size=' . $size . '&rating=' . $rating;
        return $url;
    }
}

if (!function_exists('gravatar')) {
    /**
     * Get a Gravatar img tag from email.
     *
     * @param string $email The email address
     * @param int $size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $rating (inclusive) [ g | pg | r | x ]
     * @param array $attributes Optional, additional key/value attributes to include in the IMG tag
     * @return string
     * @source http://gravatar.com/site/implement/images/php/
     */
    function gravatar($email, $size = 80, $default = 'mm', $rating = 'g', $attributes = [])
    {
        $attr = arrayToString($attributes);
        $url = gravatarUrl($email, $size, $default, $rating);
        return '<img src="' . $url . '" width="' . $size . 'px" height="' . $size . 'px" ' . trim($attr) . ' />';
    }
}

if (!function_exists('isAjax')) {

    /**
     * Determine if current page request type is ajax.
     *
     * @return bool
     */
    function isAjax()
    {
        return isNotNullOrEmptyArrayKey($_SERVER, 'HTTP_X_REQUESTED_WITH')
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

if (!function_exists('isNumberOdd')) {

    /**
     * Check if number is odd.
     *
     * @param int $num integer to check
     *
     * @return bool
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function isNumberOdd($num)
    {
        return $num % 2 !== 0;
    }
}

if (!function_exists('isNumberEven')) {

    /**
     * Check if number is even.
     *
     * @param int $num integer to check
     *
     * @return bool
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function isNumberEven($num)
    {
        return $num % 2 == 0;
    }
}

if (!function_exists('getCurrentUrlPageName')) {

    /**
     * Returns The Current URL PHP File Name.
     * Ex.: http://www.dummy.com/one/two/index.php?one=1&two=2 return index.php
     *
     * @return string
     */
    function getCurrentUrlPageName() : string
    {
        return isNotNullOrEmptyArrayKey($_SERVER, 'PHP_SELF') ? basename($_SERVER['PHP_SELF']) : '';
    }
}

if (!function_exists('getCurrentUrlQuerystring')) {

    /**
     * Returns The Current URL querystring.
     * Ex.: http://www.dummy.com/one/two/index.php?one=1&two=2 return one=1&two=2
     *
     * @return string
     */
    function getCurrentUrlQuerystring() : string
    {
        return isNotNullOrEmptyArrayKey($_SERVER, 'QUERY_STRING') ? $_SERVER['QUERY_STRING'] : '';
    }
}

if (!function_exists('getCurrentUrlDirName')) {

    /**
     * Returns The Current URL Path Name.
     * Ex.: http://www.dummy.com/one/two/index.php?one=1&two=2 return /one/two
     *
     * @return string
     */
    function getCurrentUrlDirName() : string
    {
        if (isNotNullOrEmptyArrayKey($_SERVER, 'REQUEST_URI')) {
            return dirname($_SERVER['REQUEST_URI']);
        }
        return isNotNullOrEmptyArrayKey($_SERVER, 'PHP_SELF') ? dirname($_SERVER['PHP_SELF']) : '';
    }
}

if (!function_exists('getCurrentUrlDirAbsName')) {

    /**
     * Returns The Current URL Absolute Path Name.
     * Ex.: http://www.dummy.com/one/two/index.php?one=1&two=2 return /home/user/www/one/two
     *
     * @return string
     */
    function getCurrentUrlDirAbsName() : string
    {
        return isNotNullOrEmptyArrayKey($_SERVER, 'SCRIPT_FILENAME') ? dirname($_SERVER['SCRIPT_FILENAME']) : '';
    }
}

if (!function_exists('getCurrentURL')) {

    /**
     * Return the current URL.
     * Ex.: http://www.dummy.com/one/two/index.php?one=1&two=2
     * Or
     * Ex.: https://username:passwd@www.dummy.com:443/one/two/index.php?one=1&two=2
     * @return string
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function getCurrentURL()
    {
        $url = 'http://';
        if (isHttps()) {
            $url = 'https://';
        }
        if (array_key_exists_safe($_SERVER, 'PHP_AUTH_USER')) {
            $url .= $_SERVER['PHP_AUTH_USER'];
            if (array_key_exists_safe($_SERVER, 'PHP_AUTH_PW')) {
                $url .= ':' . $_SERVER['PHP_AUTH_PW'];
            }
            $url .= '@';
        }
        if (array_key_exists_safe($_SERVER, 'HTTP_HOST')) {
            $url .= $_SERVER['HTTP_HOST'];
        }
        if (array_key_exists_safe($_SERVER, 'SERVER_PORT') && $_SERVER['SERVER_PORT'] != 80) {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }
        if (!array_key_exists_safe($_SERVER, 'REQUEST_URI')) {
            $url .= substr($_SERVER['PHP_SELF'], 1);
            if (array_key_exists_safe($_SERVER, 'QUERY_STRING')):
                $url .= '?' . $_SERVER['QUERY_STRING'];
            endif;
        } else {
            $url .= $_SERVER['REQUEST_URI'];
        }
        return $url;
    }
}

if (!function_exists('isMobile')) {

    /**
     * Detect if user is on mobile device.
     *
     * @return bool
     */
    function isMobile()
    {
        $useragent = array_key_exists_safe($_SERVER, 'HTTP_USER_AGENT') ? $_SERVER['HTTP_USER_AGENT'] : '';
        if($useragent===''){
            return false;
        }
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',
            $useragent)
        || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4));
    }
}

/**
 * Get user browser.
 *
 * @return string
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $browserName = $ub = $platform = 'Unknown';
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac OS';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $browserName = 'Internet Explorer';
        $ub = 'MSIE';
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $browserName = 'Mozilla Firefox';
        $ub = 'Firefox';
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $browserName = 'Google Chrome';
        $ub = 'Chrome';
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $browserName = 'Apple Safari';
        $ub = 'Safari';
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $browserName = 'Opera';
        $ub = 'Opera';
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $browserName = 'Netscape';
        $ub = 'Netscape';
    }
    $known = ['Version', $ub, 'other'];
    $pattern = '#(?<browser>' . implode('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    preg_match_all($pattern, $u_agent, $matches);
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent, 'Version') < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
    if ($version == null || $version == '') {
        $version = '?';
    }
    return implode(', ', [$browserName, 'Version: ' . $version, $platform]);
}

/**
 * Shorten URL via tinyurl.com service.
 * It support http or https.
 * @param string $url URL to shorten
 *
 * @return string shortend url or empty string if it fails.
 */
function getTinyUrl(string $url) : string
{
    if (!starts_with($url, 'http')) {
        $url = (isHttps() ? 'https://' : 'http://') . $url;
    }
    $gettiny = curl('http://tinyurl.com/api-create.php?url=' . $url);
    if (isNullOrEmpty($gettiny) && isNullOrEmptyArray($gettiny)) {
        return '';
    }
    if (isHttps()) {
        $gettiny = str_replace('http://', 'https://', $gettiny);
    }
    return $gettiny;
}

/**
 * Get information on a short URL. Find out where it goes.
 *
 * @param string $shortURL shortened URL
 *
 * @return string full url or empty string
 * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
 */
function expandShortUrl(string $shortURL) : string
{
    if (isNullOrEmpty($shortURL)) {
        return '';
    }
    $headers = get_headers($shortURL, 1);
    if (array_key_exists_safe($headers, 'Location')) {
        return $headers['Location'];
    }
    $data = curl($shortURL);
    preg_match_all('/<[\s]*meta[\s]*http-equiv="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si',
        $data, $match);
    if (isNullOrEmptyArray($match) || count($match) != 3
        || isNullOrEmptyArray($match[0]) || isNullOrEmptyArray($match[1]) || isNullOrEmptyArray($match[2])
        || count($match[0]) != count($match[1]) || count($match[1]) != count($match[2])
    ) {
        return '';
    }
    $originals = $match[0];
    $names = $match[1];
    $values = $match[2];
    $metaTags = [];
    for ($i = 0, $limit = count($names); $i < $limit; $i++) {
        $metaTags[$names[$i]] = ['html' => htmlentities($originals[$i]), 'value' => $values[$i]];
    }
    if (!isset($metaTags['refresh']['value']) || empty($metaTags['refresh']['value'])) {
        return '';
    }
    $returnData = explode('=', $metaTags['refresh']['value']);
    if (!isset($returnData[1]) || empty($returnData[1])) {
        return '';
    }
    return $returnData[1];
}

if (!function_exists('curl')) {

    /**
     * Make Curl call.
     *
     * @param string $url URL to curl
     * @param string $method GET or POST, Default GET
     * @param mixed $data Data to post, Default false
     * @param string[] $headers Additional headers, example: array ("Accept: application/json")
     * @param bool $returnInfo Whether or not to retrieve curl_getinfo()
     * @param string $user
     * @param string $password
     * @param bool $sslNotVerifyHostAndPeer is set to true do not check SSL certificate.
     * @param bool $followLocation
     * @param int $timeout
     * @param string $logPath if not empty write log to this file
     * @param string $referer
     * @param string $userAgent default 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0'
     * and ignore $returnInfo (i.e. call curl_getinfo even if $returnInfo is set to false).
     *
     * @return string|array if $returnInfo is set to True, array is returned with two keys, contents (will contain response) and info (information regarding a specific transfer), otherwise response content is returned
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function curl(
        $url,
        $method = 'GET',
        $data = false,
        array $headers = [],
        bool $returnInfo = false,
        string $user = '',
        string $password = '',
        bool $sslNotVerifyHostAndPeer = false,
        bool $followLocation = false,
        int $timeout = 10,
        string $logPath = '',
        string $referer = '',
        string $userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0'
    ) {
        $log = false;
        if (isNotNullOrEmpty($logPath)) {
            $log = true;
            $msg = "REQUEST_URI: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'REQUEST_URI') ? $_SERVER['REQUEST_URI'] : '') . "\r\n"
                . "SCRIPT_NAME: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'SCRIPT_NAME') ? $_SERVER['SCRIPT_NAME'] : '') . "\r\n"
                . "REMOTE ADDR: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'REMOTE_ADDR') ? $_SERVER['REMOTE_ADDR'] : '') . "\r\n"
                . "HTTP_HOST: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'HTTP_HOST') ? $_SERVER['HTTP_HOST'] : '') . "\r\n"
                . "SERVER_NAME: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'SERVER_NAME') ? $_SERVER['SERVER_NAME'] : '') . "\r\n"
                . "HTTP_X_FORWARDED_FOR: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'HTTP_X_FORWARDED_FOR') ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '') . "\r\n"
                . "SERVER_ADDR: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'SERVER_ADDR') ? $_SERVER['SERVER_ADDR'] : '') . "\r\n"
                . "REMOTE_PORT: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'REMOTE_PORT') ? $_SERVER['REMOTE_PORT'] : '') . "\r\n"
                . "HTTPS: " . (isNotNullOrEmptyArrayKey($_SERVER, 'HTTPS') ? $_SERVER['HTTPS'] : '') . "\r\n"
                . "HTTP_X_FORWARDED_PROTO: " . (isNotNullOrEmptyArrayKey($_SERVER,
                    'HTTP_X_FORWARDED_PROTO') ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : '') . "\r\n"
                . "data: " . get_var_dump_output($data) . "\r\n"
                . "headers: " . get_var_dump_output($headers) . "\r\n"
                . "returnInfo: " . $returnInfo . "\r\n"
                . "url: " . $url . "\r\n"
                . "method: " . $method . "\r\n"
                . "user: " . $user . "\r\n"
                . "password: " . (strlen($password) > 2 ? substr($password, 0, 2) . str_repeat('x',
                        10) . substr($password, -1) : 'xx') . "\r\n"
                . "sslNotVerifyHostAndPeer: " . $sslNotVerifyHostAndPeer . "\r\n"
                . "followLocation: " . $followLocation . "\r\n"
                . "timeout: " . $timeout . "\r\n"
                . "logPath: " . $logPath . "\r\n"
                . "referer: " . $referer . "\r\n"
                . "userAgent: " . $userAgent . "\r\n"
                . "\r\n";
            logToFile($logPath, $msg);
        }
        if (!function_exists('curl_init') || isNullOrEmpty($url)) {
            if ($log) {
                logToFile($logPath,
                    isNullOrEmpty($url) ? 'url is empty.curl abort.' : 'curl_init not exists.Probabily CURL is not installed.');
            }
            return '';
        }
        $ch = curl_init();
        $info = null;
        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data !== false) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        } elseif ($data !== false) {
            if (is_array($data)) {
                $dataTokens = [];
                foreach ($data as $key => $value) {
                    $dataTokens[] = urlencode($key) . '=' . urlencode($value);
                }
                $data = implode('&', $dataTokens);
            }
            $url .= (strpos($url, '?') === false ? '?' : '&') . $data;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followLocation ? true : false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout <= 1 ? 10 : $timeout);
        if ($sslNotVerifyHostAndPeer && starts_with($url, 'https://')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        if (isNotNullOrEmptyArray($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if (isNotNullOrEmpty($user) && isNotNullOrEmpty($password)) {
            curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $password);
        }
        if (isNotNullOrEmpty($referer)) {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
        if (isNotNullOrEmpty($userAgent)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        }

        if ($log) {
            logToFile($logPath, date('Y-m-d H:i:s') . "Start curl\r\n");
        }
        $contents = curl_exec($ch);
        if ($log) {
            logToFile($logPath, date('Y-m-d H:i:s') . "FINISH curl\r\n");
        }

        if ($returnInfo || $log || $contents === false || curl_errno($ch) > 0) {
            $info = curl_getinfo($ch);
            if ($log) {
                logToFile($logPath, $info, true);
            }
        }

        if ($contents === false || curl_errno($ch) > 0
            || !array_key_exists_safe($info === null ? [] : $info, 'http_code') || $info['http_code'] != 200
        ) {
            if ($log) {
                logToFile($logPath, "Error during exec CURL \r\n curl_error: " . curl_error($ch)
                    . "\r\n curl_errno: " . curl_errno($ch) . "\r\n");
            }
        } elseif ($log) {
            logToFile($logPath, "CURL IS OK\r\n RESPONSE: \r\n" . $contents);
        }

        curl_close($ch);
        return ($returnInfo ? ['contents' => $contents, 'info' => $info] : $contents);
    }
}


if (!function_exists('curl_internal_server_behind_load_balancer')) {

    /**
     * Make Curl call to one of the server behinds load balancer.
     *
     * @param string $url URL to curl
     * @param string $server_name The host name of the domain from the call will start.
     * if empty try to resolve from SERVER_NAME
     * @param string $localServerIpAddress the IP of one server to call that behinds load balancer.
     * if empty try to resolve from SERVER_ADDR
     * @param string $method GET or POST, Default GET
     * @param mixed $data Data to post, Default false
     * @param array $headers Additional headers, example: array ("Accept: application/json")
     * @param bool $returnInfo Whether or not to retrieve curl_getinfo()
     * @param string $user
     * @param string $password
     * @param bool $sslNotVerifyHostAndPeer is set to true do not check SSL certificate.
     * @param bool $followLocation
     * @param int $timeout
     * @param string $logPath if not empty write log to this file
     * @param string $referer
     * @param string $userAgent default 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0'
     * and ignore $returnInfo (i.e. call curl_getinfo even if $returnInfo is set to false).
     *
     * @return string|array if $returnInfo is set to True, array is returned with two keys, contents (will contain response) and info (information regarding a specific transfer), otherwise response content is returned
     */
    function curl_internal_server_behind_load_balancer(
        $url,
        $server_name = '',
        $localServerIpAddress = '',
        $method = 'GET',
        $data = false,
        array $headers = [],
        bool $returnInfo = false,
        string $user = '',
        string $password = '',
        bool $sslNotVerifyHostAndPeer = false,
        bool $followLocation = false,
        int $timeout = 10,
        string $logPath = '',
        string $referer = '',
        string $userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0'
    ) {
        if (isNullOrEmpty($server_name) && isNotNullOrEmptyArrayKey($_SERVER, 'SERVER_NAME')) {
            $server_name = $_SERVER['SERVER_NAME'];
        }

        if (isNullOrEmpty($server_name) && isNotNullOrEmpty($logPath)) {
            $msg = 'No server name given for calling curl ' . $url . ' behind proxy or LB';
            logToFile($logPath, $msg);
            return false;
        }

        if (isNullOrEmpty($localServerIpAddress) && isNotNullOrEmptyArrayKey($_SERVER, 'SERVER_ADDR')) {
            $localServerIpAddress = $_SERVER['SERVER_ADDR'];
        }

        if (isNullOrEmpty($localServerIpAddress) && isNotNullOrEmpty($logPath)) {
            $msg = 'No localIPAddress given for calling curl ' . $url . ' behind proxy or LB';
            logToFile($logPath, $msg);
            return false;
        }

        $url = str_replace($server_name, $localServerIpAddress, $url);

        //Using the host header to bypass a load balancer
        if (!is_array($headers)) {
            $headers = array();
        }
        $headers[] = 'Host: ' . $server_name;

        return curl($url, $method, $data, $headers, $returnInfo, $user, $password, $sslNotVerifyHostAndPeer,
            $followLocation, $timeout, $logPath, $referer, $userAgent);
    }
}

if (!function_exists('startLayoutCapture')) {

    /**
     * Turn On the output buffering.
     * @return bool
     */
    function startLayoutCapture() : bool
    {
        return ob_start();
    }
}

if (!function_exists('endLayoutCapture')) {

    /**
     * Get the buffer contents for the topmost buffer and clean it.
     * @return string
     */
    function endLayoutCapture() : string
    {
        $data = ob_get_contents();
        ob_end_clean();
        return $data === false ? '' : $data;
    }
}

if (!function_exists('get_var_dump_output')) {

    /**
     * Capture var dump $var output and return it.
     * @param $var
     * @return string
     */
    function get_var_dump_output($var)
    {
        startLayoutCapture();
        $myfunc = "var_dump";
        $myfunc($var);
        return endLayoutCapture();
    }
}

if (!function_exists('debug')) {

    /**
     * Dump information about a variable.
     *
     * @param mixed $variable Variable to debug
     *
     * @return void
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function debug($variable)
    {
        $output = get_var_dump_output($variable);
        $maps = [
            'string' => "/(string\((?P<length>\d+)\)) (?P<value>\"(?<!\\\).*\")/i",
            'array' => "/\[\"(?P<key>.+)\"(?:\:\"(?P<class>[a-z0-9_\\\]+)\")?(?:\:(?P<scope>public|protected|private))?\]=>/Ui",
            'countable' => "/(?P<type>array|int|string)\((?P<count>\d+)\)/",
            'resource' => "/resource\((?P<count>\d+)\) of type \((?P<class>[a-z0-9_\\\]+)\)/",
            'bool' => "/bool\((?P<value>true|false)\)/",
            'float' => "/float\((?P<value>[0-9\.]+)\)/",
            'object' => "/object\((?P<class>\S+)\)\#(?P<id>\d+) \((?P<count>\d+)\)/i"
        ];
        foreach ($maps as $function => $pattern) {
            $output = preg_replace_callback($pattern, function ($matches) use ($function) {
                switch ($function) {
                    case 'string':
                        $matches['value'] = htmlspecialchars($matches['value']);
                        return '<span style="color: #0000FF;">string</span>(<span style="color: #1287DB;">' . $matches['length'] . ')</span> <span style="color: #6B6E6E;">' . $matches['value'] . '</span>';
                    case 'array':
                        $key = '<span style="color: #008000;">"' . $matches['key'] . '"</span>';
                        $class = '';
                        $scope = '';
                        if (isset($matches['class']) && !empty($matches['class'])) {
                            $class = ':<span style="color: #4D5D94;">"' . $matches['class'] . '"</span>';
                        }
                        if (isset($matches['scope']) && !empty($matches['scope'])) {
                            $scope = ':<span style="color: #666666;">' . $matches['scope'] . '</span>';
                        }
                        return '[' . $key . $class . $scope . ']=>';
                    case 'countable':
                        $type = '<span style="color: #0000FF;">' . $matches['type'] . '</span>';
                        $count = '(<span style="color: #1287DB;">' . $matches['count'] . '</span>)';
                        return $type . $count;
                    case 'bool':
                        return '<span style="color: #0000FF;">bool</span>(<span style="color: #0000FF;">' . $matches['value'] . '</span>)';
                    case 'float':
                        return '<span style="color: #0000FF;">float</span>(<span style="color: #1287DB;">' . $matches['value'] . '</span>)';
                    case 'resource':
                        return '<span style="color: #0000FF;">resource</span>(<span style="color: #1287DB;">' . $matches['count'] . '</span>) of type (<span style="color: #4D5D94;">' . $matches['class'] . '</span>)';
                    case 'object':
                        return '<span style="color: #0000FF;">object</span>(<span style="color: #4D5D94;">' . $matches['class'] . '</span>)#' . $matches['id'] . ' (<span style="color: #1287DB;">' . $matches['count'] . '</span>)';
                }
            }, $output);
        }
        $header = '';
        list($debugfile) = debug_backtrace();
        if (!empty($debugfile['file'])) {
            $header = '<h4 style="border-bottom:1px solid #bbb;font-weight:bold;margin:0 0 10px 0;padding:3px 0 10px 0">' . $debugfile['file'] . '</h4>';
        }
        echo '<pre style="background-color: #CDDCF4;border: 1px solid #bbb;border-radius: 4px;-moz-border-radius:4px;-webkit-border-radius\:4px;font-size:12px;line-height:1.4em;margin:30px;padding:7px">' . $header . $output . '</pre>';
    }
}

if (!function_exists('getReferer')) {

    /**
     * Return referer page if exists otherwise return empty string.
     *
     * @return string
     */
    function getReferer() : string
    {
        return isNotNullOrEmptyArrayKey($_SERVER, 'HTTP_REFERER') ? $_SERVER['HTTP_REFERER'] : '';
    }
}

if (!function_exists('isZlibOutputCompressionActive')) {

    /**
     * Check if zlib output compression was active
     * @return bool
     */
    function isZlibOutputCompressionActive() : bool
    {
        return ini_get('zlib.output_compression') == 'On'
        || ini_get('zlib.output_compression_level') > 0
        || ini_get('output_handler') == 'ob_gzhandler';
    }
}

if (!function_exists('isZlibLoaded')) {

    /**
     * Check if zlib extension was loaded
     * @return bool
     */
    function isZlibLoaded() : bool
    {
        return extension_loaded('zlib');
    }
}

if (!function_exists('isClientAcceptGzipEncoding')) {

    /**
     * Check if client accept gzip encoding
     * @return bool
     */
    function isClientAcceptGzipEncoding() : bool
    {
        return isNotNullOrEmptyArrayKey($_SERVER, 'HTTP_ACCEPT_ENCODING')
        && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
    }
}

if (!function_exists('compressHtmlPage')) {

    /**
     * Captures output via ob_get_contents(), tries to enable gzip,
     * removes whitespace from captured output and echos back
     *
     * @return string whitespace stripped output
     * @see https://github.com/ngfw/Recipe/
     */
    function compressHtmlPage() : string
    {
        register_shutdown_function(function () {
            $buffer = str_html_compress(ob_get_contents());
            ob_end_clean();
            if (!isZlibOutputCompressionActive() &&
                isClientAcceptGzipEncoding() &&
                isZlibLoaded()
            ) {
                ob_start('ob_gzhandler');
            }
            echo $buffer;
        });
    }
}

if (!function_exists('get_http_response_code')) {

    /**
     * @param string $theURL
     * @param bool $useGet if set to true use a GET request, otherwise use a HEAD request (more fast).
     * @return int return the http status or 999 if it fails.
     */
    function get_http_response_code(string $theURL, bool $useGet = false) : int
    {
        if (isNullOrEmpty($theURL)) {
            return 999;
        }
        if (!$useGet) {
            // By default get_headers uses a GET request to fetch the headers. If you
            // want to send a HEAD request instead, you can do so using a stream context:
            stream_context_set_default(
                array(
                    'http' => array(
                        'method' => 'HEAD'
                    )
                )
            );
        }
        $headers = @get_headers($theURL);

        if ($headers === false || isNullOrEmptyArray($headers) || strlen($headers[0]) < 12) {
            return 999;
        }
        $status = substr($headers[0], 9, 3);
        return isInteger($status) ? $status : 999;
    }
}

if (!function_exists('url_exists')) {

    /**
     * Check if URL exists (return http status code <400
     * @param string $url
     * @return bool
     */
    function url_exists(string $url) : bool
    {
        return get_http_response_code($url) < 400;
    }
}

if (!function_exists('logToFile')) {

    /**
     * Log variable into log file.
     * @param string $pathFile full path with file name.
     * @param mixed $value value to log
     * @param bool $varDump default false. if set to true,
     * grab var dump output of $value and write it to log, otherwise append it to log.
     */
    function logToFile(string $pathFile, $value, bool $varDump = false)
    {
        if ($varDump) {
            $value = get_var_dump_output($value);
        }
        $f = fopen($pathFile, 'ab');
        fwrite($f, date(DATE_RFC822) . "\r\n" . $value . "\r\n----------------------------------------------\r\n");
        fclose($f);
    }
}

/**
 * Check if an extension is an image type.
 * @param  string $ext extension to check
 * @return boolean
 * @see https://github.com/kohana/ohanzee-helpers/blob/master/src/Mime.php
 */
function isImageExtension(string $ext) : bool
{
    return in_array(strtolower($ext), getImageExtensions());
}

/**
 * Get a list of common image extensions. Only types that can be read by
 * PHP's internal image methods are included!
 * @return string[]
 */
function getImageExtensions() : array
{
    return [
        'bmp',
        'gif',
        'jpeg',
        'jpg',
        'png',
        'tif',
        'tiff',
        'swf',
    ];
}

/**
 * Very simple 'template' parser. Replaces (for example) {name} with the value of $vars['name'] in strings
 *
 * @param        $str
 * @param array $vars
 * @param string $openDelimiter
 * @param string $closeDelimiter
 *
 * @return string
 * @see https://github.com/laradic/support/blob/master/src/Util.php
 * @example
 * <?php
 * $result = template('This is the best template parser. Created by {developerName}', ['developerName' => 'Radic']);
 * echo $result; // This is the best template parser. Created by Radic
 */
function template($str, array $vars = [], $openDelimiter = '{', $closeDelimiter = '}')
{
    foreach ($vars as $k => $var) {
        if (is_array($var)) {
            $str = template($str, $var);
        } elseif (is_string($var)) {
            $str = str_replace($openDelimiter . $k . $closeDelimiter, $var, $str);
        }
    }
    return $str;
}

/**
 * @param int $percent
 * @return bool
 * @see https://github.com/laradic/support/blob/master/src/Util.php
 */
function randomChance(int $percent = 50) : bool
{
    return random_int(0, 100) > 100 - $percent;
}

/**
 * @param Throwable $exception
 * @return string
 */
function getExceptionTraceAsString(\Throwable $exception)
{
    $rtn = "";
    $count = 0;
    foreach ($exception->getTrace() as $frame) {
        $args = "";
        if (isset($frame['args'])) {
            $args = [];
            foreach ($frame['args'] as $arg) {
                if (is_string($arg)) {
                    $args[] = "'" . $arg . "'";
                } elseif (is_array($arg)) {
                    $args[] = "Array";
                } elseif (is_null($arg)) {
                    $args[] = 'NULL';
                } elseif (is_bool($arg)) {
                    $args[] = ($arg) ? "true" : "false";
                } elseif (is_object($arg)) {
                    $args[] = get_class($arg);
                } elseif (is_resource($arg)) {
                    $args[] = get_resource_type($arg);
                } else {
                    $args[] = $arg;
                }
            }
            $args = implode(", ", $args);
        }
        $rtn .= sprintf("#%s %s(%s): %s(%s)\n",
            $count,
            isset($frame['file']) ? $frame['file'] : '',
            isset($frame['line']) ? $frame['line'] : '',
            $frame['function'],
            $args);
        $count++;
    }
    return $rtn;
}

if (!function_exists('windows_os')) {
    /**
     * Determine whether the current environment is Windows based.
     *
     * @return bool
     *
     * @see https://github.com/illuminate/support/blob/master/helpers.php
     */
    function windows_os() : bool
    {
        return strtolower(substr(PHP_OS, 0, 3)) === 'win';
    }
}

if (!function_exists('getConsoleColorTagForStatusCode')) {
    /**
     * Get the color tag for the given status code to be use in symfony/laravel console.
     *
     * @param string $code
     *
     * @return string
     *
     * @see https://github.com/spatie/http-status-check/blob/master/src/CrawlLogger.php#L96
     */
    function getConsoleColorTagForStatusCode($code)
    {
        if (starts_with($code, '2')) {
            return 'info';
        }
        if (starts_with($code, '3')) {
            return 'comment';
        }
        return 'error';
    }
}

if (!function_exists('is_32bit')) {
    /**
     * Check if the OS architecture is 32bit.
     *
     * @return bool
     *
     * @see http://stackoverflow.com/questions/6303241/find-windows-32-or-64-bit-using-php
     */
    function is_32bit() : bool
    {
        return get_os_architecture()==32;
    }
}

if (!function_exists('is_64bit')) {
    /**
     * Check if the OS architecture is 64bit.
     *
     * @return bool
     *
     * @see http://stackoverflow.com/questions/6303241/find-windows-32-or-64-bit-using-php
     */
    function is_64bit() : bool
    {
        return get_os_architecture()==64;
    }
}

if (!function_exists('get_os_architecture')) {
    /**
     * Get the OS architecture 32 or 64 bit.
     *
     * @return int return 32 or 64 if ok, otherwise return 0.
     *
     * @see http://stackoverflow.com/questions/6303241/find-windows-32-or-64-bit-using-php
     */
    function get_os_architecture() : int
    {
        switch(PHP_INT_SIZE) {
            case 4:
                return 32;
            case 8:
                return 64;
            default:
                return 0;
        }
    }
}


if (!function_exists('isRequestFromCloudFlare')) {

    /**
     * Check if request (by given $_SERVER) is a cloudflare request
     * @param $server
     * @return bool
     */
    function isRequestFromCloudFlare(array $server) : bool
    {
        if (!isset($server['HTTP_CF_CONNECTING_IP']) || !isIP($server['HTTP_CF_CONNECTING_IP'])) {
            return false;
        }

        if (isCloudFlareIp($server['REMOTE_ADDR'])) {
            return true;
        }

        //in case proxy or load balancer in front the server web
        //the chained passed IPs are in the HTTP_X_FORWARDED_FOR var in these two possible ways:
        //2.38.87.88
        //2.38.87.88, 188.114.101.5
        if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return false;
        }

        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') === false) {
            return isCloudFlareIp($server['HTTP_X_FORWARDED_FOR']);
        }

        $IPs = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if (empty($IPs) || !is_array($IPs) || count($IPs) < 1) {
            return false;
        }

        //usually the cloudflare ip are the last IP in the stack, so start loop by last entries
        for ($i = count($IPs) - 1; $i >= 0; $i--) {
            $ip = trim($IPs[$i]);
            if (isCloudFlareIp($ip)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('isCloudFlareIp')) {

    /**
     * Check if given ip is a valid cloudflare ip.
     * @param $ip
     * @return bool
     */
    function isCloudFlareIp($ip) : bool
    {
        if (!isIP($ip)) {
            return false;
        }

        //array given from https://www.cloudflare.com/ips-v4
        $cf_ip_ranges = array(
            '103.21.244.0/22',
            '103.22.200.0/22',
            '103.31.4.0/22',
            '104.16.0.0/12',
            '108.162.192.0/18',
            '131.0.72.0/22',
            '141.101.64.0/18',
            '162.158.0.0/15',
            '172.64.0.0/13',
            '173.245.48.0/20',
            '188.114.96.0/20',
            '190.93.240.0/20',
            '197.234.240.0/22',
            '198.41.128.0/17',
        );

        foreach ($cf_ip_ranges as $range) {
            if (ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('gzCompressFile')) {

    /**
     * GZIPs a file on disk (appending .gz to the name)
     *
     * From http://stackoverflow.com/questions/6073397/how-do-you-create-a-gz-file-using-php
     * Based on function by Kioob at:
     * http://www.php.net/manual/en/function.gzwrite.php#34955
     *
     * @param string $source Path to file that should be compressed
     * @param integer $level GZIP compression level (default: 9)
     * @return string New filename (with .gz appended) if success, or false if operation fails
     */
    function gzCompressFile($source, $level = 9)
    {
        $dest = $source . '.gz';
        $mode = 'wb' . $level;
        $error = false;
        if ($fp_out = gzopen($dest, $mode)) {
            if ($fp_in = fopen($source, 'rb')) {
                while (!feof($fp_in))
                    gzwrite($fp_out, fread($fp_in, 1024 * 512));
                fclose($fp_in);
            } else {
                $error = true;
            }
            gzclose($fp_out);
        } else {
            $error = true;
        }
        if ($error)
            return false;
        else
            return $dest;
    }
}
