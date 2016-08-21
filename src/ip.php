<?php

/**
 * @param array $server
 * @return string
 */
function getIPVisitor(array $server = []) : string
{
    if (empty($server)) {
        return '';
    }

    if (array_key_exists('HTTP_X_FORWARDED_FOR', $server) && trim($server['HTTP_X_FORWARDED_FOR']) != '') {
        $IP2Check = $server['HTTP_X_FORWARDED_FOR'];
    } elseif (array_key_exists('REMOTE_ADDR', $server) && trim($server['REMOTE_ADDR'])) {
        $IP2Check = $server['REMOTE_ADDR'];
    }

    if ($IP2Check == '') {
        return '';
    } elseif (strpos($IP2Check, ',') === false) {
        return $IP2Check;
    } else {
        // Header can contain multiple IP-s of proxies that are passed through.
        // Only the IP added by the last proxy (last IP in the list) can be trusted.
        $client_ip = trim(end(explode(',', $IP2Check)));
        return $client_ip;
    }
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
