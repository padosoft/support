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

    return trim(head($arrIps));
}

/**
 * anonimizeIp masquerade last 3 digit of IP address
 * @param  string $ip
 * @return string  masked IP
 */
function anonimizeIp(string $ip) : string
{
    if ($ip === null || strlen($ip) < 2 || strrpos($ip, ".") === false) {
        return $ip;
    }
    return substr($ip, 0, strrpos($ip, ".") + 1) . '0';
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
