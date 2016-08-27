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
 * Encrypt string.
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
    $protocol = 'http://';
    if (isHttps()) {
        $protocol = 'https://';
    }

    $apiUrl = $protocol . 'www.google.com/s2/favicons?domain=';
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
 *
 * @return bool
 */
function isHttps()
{
    return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
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
        $url = 'http://www.gravatar.com';
        if (isHttps()) {
            $url = 'https://secure.gravatar.com';
        }
        $url .= '/avatar.php?gravatar_id=' . md5(strtolower(trim($email))) . '&default=' . $default . '&size=' . $size . '&rating=' . $rating;
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
        return $_SERVER !== null && array_key_exists_safe($_SERVER,
            'HTTP_X_REQUESTED_WITH') && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
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

if (!function_exists('getCurrentURL')) {

    /**
     * Return the current URL.
     *
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
        $useragent = $_SERVER['HTTP_USER_AGENT'];
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
     * @param mixed $headers Additional headers, example: array ("Accept: application/json")
     * @param bool $returnInfo Whether or not to retrieve curl_getinfo()
     *
     * @return string|array if $returnInfo is set to True, array is returned with two keys, contents (will contain response) and info (information regarding a specific transfer), otherwise response content is returned
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function curl($url, $method = 'GET', $data = false, $headers = false, $returnInfo = false)
    {
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
                    array_push($dataTokens, urlencode($key) . '=' . urlencode($value));
                }
                $data = implode('&', $dataTokens);
            }
            $url .= '?' . $data;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if ($headers !== false) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $contents = curl_exec($ch);
        if ($returnInfo) {
            $info = curl_getinfo($ch);
        }
        curl_close($ch);
        return ($returnInfo ? ['contents' => $contents, 'info' => $info] : $contents);
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
        ob_start();
        var_dump($variable);
        $output = ob_get_clean();
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
