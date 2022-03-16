<?php

/**
 * get Client IP. DEPRECATED! use getClientIP().
 * @param array $server
 * @return string
 * @deprecated
 * @see getClientIp();
 */
function getIPVisitor(array $server = []) : string
{
    $IP2Check = '';
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $server) && trim($server['HTTP_X_FORWARDED_FOR']) != '') {
        $IP2Check = $server['HTTP_X_FORWARDED_FOR'];
    } elseif (array_key_exists('REMOTE_ADDR', $server) && trim($server['REMOTE_ADDR'])) {
        $IP2Check = $server['REMOTE_ADDR'];
    }

    if (strpos($IP2Check, ',') === false) {
        return $IP2Check;
    }

    // Header can contain multiple IP-s of proxies that are passed through.
    // Only the IP added by the last proxy (last IP in the list) can be trusted.
    $arrIps = explode(',', $IP2Check);
    if (!is_array($arrIps) || count($arrIps) < 1) {
        return '';
    }

    return trim(headEx($arrIps));
}

/**
 * anonimizeIp masquerade last digit of IP address.
 * With bad ip argument return 0.0.0.0
 * Support IPv4, Ipv6 and IPv4 Compatibility.
 * @param  string $ip
 * @return string  masked IP
 */
function anonimizeIp(string $ip) : string
{
    if (isIPv4($ip)) {
        return anonimizeIpv4($ip);
    } elseif (isIPv4Compatibility($ip)) {
        return anonimizeIpv4Compatibility($ip);
    }
    return anonimizeIpv6($ip);
}

/**
 * masquerade last digit of IP address.
 * With bad ip argument return 0.0.0.0
 * @param string $ip
 * @return string
 */
function anonimizeIpv4Compatibility(string $ip):string
{
    if (isIPv4Compatibility($ip)) {
        return substr($ip, 0, strrpos($ip, ".") + 1) . '0';
    }
    return '0.0.0.0';
}

/**
 * masquerade last digit of IP address with inet php functions.
 * With bad ip argument return 0.0.0.0
 * @param string $ip
 * @return string
 */
function anonimizeIpWithInet(string $ip):string
{
    if ($ip = @inet_pton($ip)) {
        return inet_ntop(substr($ip, 0, strlen($ip) / 2) . str_repeat(chr(0), strlen($ip) / 2));
    }
    return '0.0.0.0';
}

/**
 * masquerade last digit of IP address.
 * With bad ip argument return 0.0.0.0
 * @param string $ip
 * @return string
 */
function anonimizeIpv6(string $ip):string
{
    if (strrpos($ip, ":") > 0) {
        return substr($ip, 0, strrpos($ip, ":") + 1) . '0';
    }
    return '0.0.0.0';
}

/**
 * masquerade last digit of IP address.
 * With bad ip argument return 0.0.0.0
 * @param string $ip
 * @return string
 */
function anonimizeIpv4(string $ip):string
{
    if (isIPv4($ip)) {
        return substr($ip, 0, strrpos($ip, ".") + 1) . '0';
    }
    return '0.0.0.0';
}

/**
 * getHost Get the Internet host name corresponding to a given IP address
 * @param  string $ip the IP to resolve.
 * @return string    Returns the host name of the Internet host specified by ip_address on success,
 *                   the unmodified ip_address on failure,
 *                   or FALSE on malformed input.
 */
function getHost($ip)
{
    return gethostbyaddr($ip);
}

/**
 * Returns the client IP addresses.
 *
 * In the returned array the most trusted IP address is first, and the
 * least trusted one last. The "real" client IP address is the last one,
 * but this is also the least trusted one.
 * Trusted proxies are stripped if passed.
 *
 * Use this method carefully; you should use getClientIp() instead.
 *
 * @param array $server
 * @param array $trustedProxies
 *
 * @return array The client IP addresses
 *
 * @see getClientIp()
 * @see https://github.com/symfony/symfony/blob/0b39ce23150c34c990e3eccfae4e375161c0d352/src/Symfony/Component/HttpFoundation/Request.php#L831
 */
function getClientIps(array $server, array $trustedProxies = []) : array
{
    if (empty($server)) {
        return [''];
    }
    $clientIps = array();
    $ip = $server['REMOTE_ADDR'];
    if (!isFromTrustedProxy($trustedProxies, $ip)) {
        return array($ip);
    }
    if (array_key_exists('FORWARDED', $server) && trim($server['FORWARDED']) != '') {
        $forwardedHeader = $server['FORWARDED'];
        preg_match_all('{(for)=("?\[?)([a-z0-9\.:_\-/]*)}', $forwardedHeader, $matches);
        $clientIps = $matches[3];
    } elseif (array_key_exists('X_FORWARDED_FOR', $server) && trim($server['X_FORWARDED_FOR']) != '') {
        $clientIps = array_map('trim', explode(',', $server['X_FORWARDED_FOR']));
    }
    $clientIps[] = $ip; // Complete the IP chain with the IP the request actually came from
    $ip = $clientIps[0]; // Fallback to this when the client IP falls into the range of trusted proxies
    foreach ($clientIps as $key => $clientIp) {
        // Remove port (unfortunately, it does happen)
        if (preg_match('{((?:\d+\.){3}\d+)\:\d+}', $clientIp, $match)) {
            $clientIps[$key] = $clientIp = $match[1];
        }
        if (checkIp($clientIp, $trustedProxies)) {
            unset($clientIps[$key]);
        }
    }
    // Now the IP chain contains only untrusted proxies and the client IP
    return $clientIps ? array_reverse($clientIps) : array($ip);
}

/**
 * Returns the client IP address.
 *
 * This method can read the client IP address from the "X-Forwarded-For" header
 * when trusted proxies were set via "setTrustedProxies()". The "X-Forwarded-For"
 * header value is a comma+space separated list of IP addresses, the left-most
 * being the original client, and each successive proxy that passed the request
 * adding the IP address where it received the request from.
 *
 * If your reverse proxy uses a different header name than "X-Forwarded-For",
 * ("Client-Ip" for instance), configure it via "setTrustedHeaderName()" with
 * the "client-ip" key.
 *
 * @param array $server
 * @param array $trustedProxies
 *
 * @return string The client IP address
 *
 * @see getClientIps()
 * @see https://github.com/symfony/symfony/blob/0b39ce23150c34c990e3eccfae4e375161c0d352/src/Symfony/Component/HttpFoundation/Request.php#L886
 * @see http://en.wikipedia.org/wiki/X-Forwarded-For
 *
 */
function getClientIp(array $server, array $trustedProxies = []) : string
{
    $ipAddresses = getClientIps($server, $trustedProxies);
    return $ipAddresses[0];
}

/**
 * Checks if an IPv4 or IPv6 address is contained in the list of given IPs or subnets.
 *
 * @param string $requestIp IP to check
 * @param string|array $ips List of IPs or subnets (can be a string if only a single one)
 *
 * @return bool Whether the IP is valid
 * @see https://github.com/symfony/http-foundation/blob/master/IpUtils.php
 */
function checkIp($requestIp, $ips) : bool
{
    if (empty($ips)) {
        return false;
    }
    if (!is_array($ips)) {
        $ips = array($ips);
    }
    $method = substr_count($requestIp, ':') > 1 ? 'checkIp6' : 'checkIp4';
    foreach ($ips as $ip) {
        if ($method($requestIp, $ip)) {
            return true;
        }
    }
    return false;
}

/**
 * Compares two IPv4 addresses.
 * In case a subnet is given, it checks if it contains the request IP.
 *
 * @param string $requestIp IPv4 address to check
 * @param string $ip IPv4 address or subnet in CIDR notation
 *
 * @return bool Whether the request IP matches the IP, or whether the request IP is within the CIDR subnet
 * @see https://github.com/symfony/http-foundation/blob/master/IpUtils.php
 */
function checkIp4($requestIp, $ip) : bool
{
    if (false !== strpos($ip, '/')) {
        list($address, $netmask) = explode('/', $ip, 2);
        if ($netmask === '0') {
            // Ensure IP is valid - using ip2long below implicitly validates, but we need to do it manually here
            return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        if ($netmask < 0 || $netmask > 32) {
            return false;
        }
    } else {
        $address = $ip;
        $netmask = 32;
    }
    return 0 === substr_compare(sprintf('%032b', ip2long($requestIp)), sprintf('%032b', ip2long($address)), 0,
        $netmask);
}

/**
 * Compares two IPv6 addresses.
 * In case a subnet is given, it checks if it contains the request IP.
 *
 * @author David Soria Parra <dsp at php dot net>
 *
 * @see https://github.com/symfony/http-foundation/blob/master/IpUtils.php
 * @see https://github.com/dsp/v6tools
 *
 * @param string $requestIp IPv6 address to check
 * @param string $ip IPv6 address or subnet in CIDR notation
 *
 * @return bool Whether the IP is valid
 *
 * @throws \RuntimeException When IPV6 support is not enabled
 */
function checkIp6($requestIp, $ip) : bool
{
    if (!((extension_loaded('sockets') && defined('AF_INET6')) || @inet_pton('::1'))) {
        throw new \RuntimeException('Unable to check Ipv6. Check that PHP was not compiled with option "disable-ipv6".');
    }
    if (false !== strpos($ip, '/')) {
        list($address, $netmask) = explode('/', $ip, 2);
        if ($netmask < 1 || $netmask > 128) {
            return false;
        }
    } else {
        $address = $ip;
        $netmask = 128;
    }
    $bytesAddr = unpack('n*', @inet_pton($address));
    $bytesTest = unpack('n*', @inet_pton($requestIp));
    if (empty($bytesAddr) || empty($bytesTest)) {
        return false;
    }
    for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; ++$i) {
        $left = $netmask - 16 * ($i - 1);
        $left = ($left <= 16) ? $left : 16;
        $mask = ~(0xffff >> $left) & 0xffff;
        if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
            return false;
        }
    }
    return true;
}

/**
 * Check if $ip is contained in $trustedProxies array.
 * @param array $trustedProxies
 * @param $ip
 * @return bool
 */
function isFromTrustedProxy(array $trustedProxies, $ip)
{
    return !empty($trustedProxies) && checkIp($ip, $trustedProxies);
}

/**
 * Convert an IPv4 address to IPv6
 *
 * @param string IP Address in dot notation (192.168.1.100)
 * @return string IPv6 formatted address or false if invalid input
 * @see http://stackoverflow.com/questions/444966/working-with-ipv6-addresses-in-php
 */
function iPv4To6($ip)
{
    if(isNullOrEmpty($ip)){
        return '';
    }

    static $Mask = '::ffff:'; // This tells IPv6 it has an IPv4 address
    $IPv6 = (strpos($ip, '::') === 0);
    $IPv4 = (strpos($ip, '.') > 0);

    if (!$IPv4 && !$IPv6) {
        return false;
    }
    if ($IPv6 && $IPv4) {
        // Strip IPv4 Compatibility notation
        $ip = substr($ip, strrpos($ip, ':') + 1);
    } elseif (!$IPv4) {
        // Seems to be IPv6 already?
        return $ip;
    }
    $ip = array_pad(explode('.', $ip), 4, 0);
    if (count($ip) > 4) {
        return false;
    }
    for ($i = 0; $i < 4; $i++) {
        if ($ip[$i] > 255) {
            return false;
        }
    }

    $Part7 = base_convert(($ip[0] * 256) + $ip[1], 10, 16);
    $Part8 = base_convert(($ip[2] * 256) + $ip[3], 10, 16);
    return $Mask . $Part7 . ':' . $Part8;
}

/**
 * Replace '::' with appropriate number of ':0'
 * @param $ip
 * @return mixed|string
 * @see http://stackoverflow.com/questions/12095835/quick-way-of-expanding-ipv6-addresses-with-php
 */
function expandIPv6Notation($ip)
{
    if (!iPv4To6($ip)) {
        return $ip;
    }
    $hex = unpack("H*hex", inet_pton($ip));
    $ip = substr(preg_replace("/([A-f0-9]{4})/", "$1:", $hex['hex']), 0, -1);
    return $ip;
}

/**
 * decbin32
 * In order to simplify working with IP addresses (in binary) and their
 * netmasks, it is easier to ensure that the binary strings are padded
 * with zeros out to 32 characters - IP addresses are 32 bit numbers
 *
 * @param $dec
 * @return string
 */
function decbin32 ($dec)
{
    return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
}

/**
 * ipInRange
 *
 * Function to determine if an IP is located in a
 * specific range as specified via several alternative formats.
 *
 * This function takes 2 arguments, an IP address and a "range" in several* different formats.
 *
 * Network ranges can be specified as:
 * 1. Wildcard format:     1.2.3.*
 * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
 * 3. Start-End IP format: 1.2.3.0-1.2.3.255
 *
 * Return value BOOLEAN : ipInRange($ip, $range);
 * The function will return true if the supplied IP is within the range.
 *
 * Note little validation is done on the range inputs - it expects you to use one of the above 3 formats.
 *
 * Copyright 2008: Paul Gregg <pgregg@pgregg.com>
 * 10 January 2008
 * Version: 1.2
 *
 * Source website: http://www.pgregg.com/projects/php/ip_in_range/
 * Version 1.2
 *
 * This software is Donationware - if you feel you have benefited from
 * the use of this tool then please consider a donation. The value of
 * which is entirely left up to your discretion.
 * http://www.pgregg.com/donate/
 *
 * Please do not remove this header, or source attibution from this file.
 *
 * @param $ip
 * @param $range Network ranges can be specified as: 1. Wildcard format 1.2.3.*, 2. CIDR format: 1.2.3/24  OR  1.2.3.4/255.255.255.0, 3. Start-End IP format 1.2.3.0-1.2.3.255
 * @return bool The function will return true if the supplied IP is within the range.
 */
function ipInRange($ip, $range) : bool
{
    if(!is_string($ip) || !is_string($range)){
        return false;
    }

    if (strpos($range, '/') !== false) {
        // $range is in IP/NETMASK format
        list($range, $netmask) = explode('/', $range, 2);
        if (strpos($netmask, '.') !== false) {
            // $netmask is a 255.255.0.0 format
            $netmask = str_replace('*', '0', $netmask);
            $netmask_dec = ip2long($netmask);
            return ( (ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec) );
        } else {
            // $netmask is a CIDR size block
            // fix the range argument
            $x = explode('.', $range);
            while(count($x)<4) $x[] = '0';
            list($a,$b,$c,$d) = $x;
            $range = sprintf("%u.%u.%u.%u", empty($a)?'0':$a, empty($b)?'0':$b,empty($c)?'0':$c,empty($d)?'0':$d);
            $range_dec = ip2long($range);
            $ip_dec = ip2long($ip);

            # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
            #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

            # Strategy 2 - Use math to create it
            $wildcard_dec = pow(2, (32-$netmask)) - 1;
            $netmask_dec = ~ $wildcard_dec;

            return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
        }
    } else {
        // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
        if (strpos($range, '*') !==false) { // a.b.*.* format
            // Just convert to A-B format by setting * to 0 for A and 255 for B
            $lower = str_replace('*', '0', $range);
            $upper = str_replace('*', '255', $range);
            $range = "$lower-$upper";
        }

        if (strpos($range, '-')!==false) { // A-B format
            list($lower, $upper) = explode('-', $range, 2);
            $lower_dec = (float)sprintf("%u",ip2long($lower));
            $upper_dec = (float)sprintf("%u",ip2long($upper));
            $ip_dec = (float)sprintf("%u",ip2long($ip));
            return ( ($ip_dec>=$lower_dec) && ($ip_dec<=$upper_dec) );
        }
        return false;
    }
}
