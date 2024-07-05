<?php

/**
 * Check if a string number starts with one ore more zero
 * i.e.: 00...000 or 000...0Xxxx.x  with X an int
 * @param $value
 * @return bool
 */
function isStringNumberStartsWithMoreThanOneZero($value)
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    return preg_match('/^[0]{2,}$/', $value) === 1 || preg_match('/^0{1,}[1-9]{1,}$/', $value) === 1;
}

/**
 * Check if the value (int, float or string) is a integer and greater than zero..
 * Only number >0 and <=PHP_INT_MAX
 * or if $acceptIntegerFloatingPoints==true a floating point that match an positive integer).
 * @param $value
 * @param bool $acceptIntegerFloatingPoints
 * @return bool
 */
function isIntegerPositive($value, $acceptIntegerFloatingPoints = false): bool
{
    return isInteger($value, true, $acceptIntegerFloatingPoints) && $value > 0;
}

/**
 * Check if the value (int, float or string) is a integer and less than zero..
 * Only number <0 and >=PHP_INT_MIN
 * or if $acceptIntegerFloatingPoints==true a floating point that match an negative integer).
 * @param $value
 * @param bool $acceptIntegerFloatingPoints
 * @return bool
 */
function isIntegerNegative($value, $acceptIntegerFloatingPoints = false): bool
{
    return isInteger($value, false, $acceptIntegerFloatingPoints) && $value < 0;
}

/**
 * Check if the value (int, float or string) is a integer and greater than zero or equals to zero.
 * Only number >=0 and <=PHP_INT_MAX
 * or if $acceptIntegerFloatingPoints==true a floating point that match an positive integer).
 * @param $value
 * @param bool $acceptIntegerFloatingPoints
 * @return bool
 */
function isIntegerPositiveOrZero($value, $acceptIntegerFloatingPoints = false): bool
{
    return isInteger($value, true, $acceptIntegerFloatingPoints) && $value >= 0;
}

/**
 * Check if the value (int, float or string) is a integer and less than zero or equals to zero.
 * Only number <=0 and >=PHP_INT_MIN
 * or if $acceptIntegerFloatingPoints==true a floating point that match an negative integer).
 * @param $value
 * @param bool $acceptIntegerFloatingPoints
 * @return bool
 */
function isIntegerNegativeOrZero($value, $acceptIntegerFloatingPoints = false): bool
{
    return isInteger($value, false, $acceptIntegerFloatingPoints) && $value <= 0;
}

/**
 * Check if the value (int, float or string) is a integer and equals to zero.
 * If $acceptIntegerFloatingPoints==true a floating point that match an zero integer).
 * @param $value
 * @param bool $acceptIntegerFloatingPoints default false
 * @param bool $acceptSign default false if set to true accept -0 and +0 otherwise accept 0.
 * @return bool
 */
function isIntegerZero($value, $acceptIntegerFloatingPoints = false, $acceptSign = false): bool
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    if (!$acceptSign) {
        return $value == 0 && isNumericWithoutSign($value) && !isStringNumberStartsWithMoreThanOneZero($value);
    } else {
        return abs($value) == 0 && isInteger(abs($value), !$acceptSign, $acceptIntegerFloatingPoints);
    }
}

/**
 * Check if the value (int, float or string) is a integer.
 * Only number <=PHP_INT_MAX (and >=PHP_INT_MIN if unsigned=true)
 * or if $acceptIntegerFloatingPoints==true a floating point that match an integer).
 * @param $value
 * @param bool $unsigned
 * @param bool $acceptIntegerFloatingPoints
 * @return bool
 */
function isInteger($value, $unsigned = true, $acceptIntegerFloatingPoints = false): bool
{
    if (isStringNumberStartsWithMoreThanOneZero($value)) {
        return false;
    }

    //accept only integer number and if $acceptIntegerFloatingPoints is true accept integer floating point too.
    return ((preg_match('/^' . ($unsigned ? '' : '-{0,1}') . '[0-9]{1,}$/', $value) === 1
            && ($value <= PHP_INT_MAX && $value >= PHP_INT_MIN && (((int)$value) == $value))
        )
        || ($acceptIntegerFloatingPoints && isIntegerFloatingPoint($value, $unsigned)));
}

/**
 * Check if string is a valid floating point that
 * match an integer (<=PHP_INT_MAX and >=PHP_INT_MIN if unsigned=true)
 * or is an integer
 * Ex.: 1, 1e2, 1E2, 1e+2, 1e-2, 1.4e+2, -1.2e+2, -1.231e-2 etc...
 * @param $value
 * @param bool $unsigned
 * @return bool
 */
function isIntegerFloatingPoint($value, $unsigned = true): bool
{
    return isFloatingPoint($value, $unsigned)
        && $value <= PHP_INT_MAX && $value >= PHP_INT_MIN
        //big number rouned to int aproximately!
        //big number change into exp format
        && ((int)((double)$value) == $value || (int)$value == $value || strpos(strtoupper((string)$value),
                                                                               'E') === false);
}

/**
 * Check if string is a valid floating point.
 * Ex.: [+-]1, [+-]1e2, [+-]1E2, [+-]1e+2, [+-]1e-2, [+-]1.43234e+2, -1.231e+2, -1.231e-2 etc...
 * @param $value
 * @param $unsigned
 * @return bool
 */
function isFloatingPoint($value, $unsigned): bool
{
    if ($value === null) {
        return false;
    }

    if (isStringNumberStartsWithMoreThanOneZero($value)) {
        return false;
    }

    return preg_match('/^' . ($unsigned ? '[+]{0,1}' : '[-+]{0,1}') . '[0-9]{1,}(\.[0-9]{1,}){0,1}([Ee][+,-]{0,1}[0-9]{1,}){0,}$/',
                      $value) === 1;
}

/**
 * Check if the value is a integer/string 0 or 1.
 * @param $value
 * @return bool
 */
function isIntBool($value): bool
{
    return $value === 1 || $value === 0 || $value === '1' || $value === '0';
}

/**
 * Check if the value are a double (integer or float in the form 1, 1.11...1.
 * @param $value
 * @param int $dec
 * @param bool $unsigned
 * @param bool $exactDec if set to true aspect number of dec exact to $dec,
 * otherwise $dec is max decimals accepted (0 decimals are also ok in this case).
 * if $dec is an empty string, accept 0 to infinite decimals.
 * @return bool
 */
function isDouble($value, $dec = 2, $unsigned = true, $exactDec = false): bool
{
    if ($value === null) {
        return false;
    }

    if (isStringNumberStartsWithMoreThanOneZero($value)) {
        return false;
    }
    $regEx = '/^' . ($unsigned ? '' : '-{0,1}') . '[0-9]{1,}(\.{1}[0-9]{' . ($exactDec ? '' : '1,') . $dec . '})' . ($exactDec ? '{1}' : '{0,1}') . '$/';
    return preg_match($regEx, $value) === 1;
}

/**
 * Check if a string is a percent 0%-100%
 * @param $value
 * @param bool $withDecimal if set to true accept decimal values.
 * @param bool $withPercentChar if set to true require % char, otherwise if find a % char return false.
 * @return bool
 */
function isPercent($value, bool $withDecimal = true, bool $withPercentChar = false): bool
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    $contains_perc = str_containsEx($value, '%');
    if (($withPercentChar && !$contains_perc)
        || (!$withPercentChar && $contains_perc)
        || (substr_count($value, '%') > 1) //only one %
    ) {
        return false;
    }
    $value = trim(str_replace('%', '', $value));
    return $withDecimal ? isDouble($value, '', true) : isInteger($value, true);
}

/**
 * @param float $value
 * @param float $leftRange
 * @param float $rightRange
 * @return bool
 */
function isInRange(float $value, float $leftRange = 0.00, float $rightRange = 0.00): bool
{
    return ($value <= $rightRange && $value >= $leftRange);
}

/**
 * Check if string is dd/mm/YYYY
 * @param $value
 * @return bool
 */
function isDateIta($value): bool
{
    if (isNullOrEmpty($value) || strlen($value) != 10 || strpos($value, '/') === false) {
        return false;
    }
    $strRegExp = '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/';
    if (!(preg_match($strRegExp, $value) === 1)) {
        return false;
    }
    [$dd, $mm, $yyyy] = explode('/', $value);
    try {
        return checkdate((int)$mm, (int)$dd, (int)$yyyy);
    } catch (\Throwable $e) {
        return false;
    }
}

/**
 * Check if string is 0000-00-00
 * @param $value
 * @return bool
 */
function isDateZeroIso($value): bool
{
    return $value == '0000-00-00';
}

/**
 * Check if string is 00:00:00
 * @param $value
 * @return bool
 */
function isTimeZeroIso($value): bool
{
    return $value == '00:00:00';
}

/**
 * Check if string is '0000-00-00 00:00:00'
 * @param $value
 * @return bool
 */
function isDateTimeZeroIso($value): bool
{
    return $value == '0000-00-00 00:00:00';
}

/**
 * Check if string is YYYY-mm-dd and valid date or 0000-00-00
 * @param $value
 * @return bool
 */
function isDateOrDateZeroIso($value): bool
{
    return isDateIso($value) || isDateZeroIso($value);
}

/**
 * Check if string is 'YYYY-mm-dd HH:ii:ss' and valid date or '0000-00-00 00:00:00'
 * @param $value
 * @return bool
 */
function isDateTimeOrDateTimeZeroIso($value): bool
{
    return isDateTimeIso($value) || isDateTimeZeroIso($value);
}


/**
 * Check if string is 00/00/0000
 * @param $value
 * @return bool
 */
function isDateZeroIta($value): bool
{
    return $value == '00/00/0000';
}

/**
 * Check if string is 00:00:00
 * @param $value
 * @return bool
 */
function isTimeZeroIta($value): bool
{
    return $value == '00:00:00';
}

/**
 * Check if string is '00/00/0000 00:00:00'
 * @param $value
 * @return bool
 */
function isDateTimeZeroIta($value): bool
{
    return $value == '00/00/0000 00:00:00';
}

/**
 * Check if string is dd/mm/YYYY and valid date or 00/00/0000
 * @param $value
 * @return bool
 */
function isDateOrDateZeroIta($value): bool
{
    return isDateIta($value) || isDateZeroIta($value);
}

/**
 * Check if string is 'dd/mm/YYYY HH:ii:ss' and valid date or '00/00/0000 00:00:00'
 * @param $value
 * @return bool
 */
function isDateTimeOrDateTimeZeroIta($value): bool
{
    return isDateTimeIta($value) || isDateTimeZeroIta($value);
}

/**
 * Check if string is YYYY-mm-dd
 * @param $value
 * @return bool
 */
function isDateIso($value): bool
{
    if (isNullOrEmpty($value) || strlen($value) != 10 || strpos($value, '-') === false) {
        return false;
    }
    $strRegExp = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
    if (!(preg_match($strRegExp, $value) === 1)) {
        return false;
    }
    [$yyyy, $mm, $dd] = explode('-', $value);
    try {
        return checkdate((int)$mm, (int)$dd, (int)$yyyy);
    } catch (\Throwable $e) {
        return false;
    }
}

/**
 * Check if string is YYYY-mm-dd HH:ii:ss
 * @param $value
 * @return bool
 */
function isDateTimeIso($value): bool
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    if (!isDateIso(substr($value, 0, 10))) {
        return false;
    }
    return isTimeIso(substr($value, 11));
}

/**
 * Check if string is dd/mm/YYYY HH:ii:ss
 * @param $value
 * @return bool
 */
function isDateTimeIta($value): bool
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    if (!isDateIta(substr($value, 0, 10))) {
        return false;
    }
    return isTimeIso(substr($value, 11));
}

/**
 * Check if string is HH:ii:ss
 * @param $value
 * @return bool
 */
function isTimeIso($value): bool
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    $strRegExp = '/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/';
    if (!(preg_match($strRegExp, $value) === 1)) {
        return false;
    }
    list($HH, $ii, $ss) = explode(':', $value);
    return isInRange((int)$HH, 0, 23) && isInRange((int)$ii, 0, 59) && isInRange((int)$ss, 0, 59);
}

/**
 * An alias of isTimeIso.
 * @param $value
 * @return bool
 */
function isTimeIta($value)
{
    return isTimeIso($value);
}

/**
 * Check if year ia a leap year in jewish calendar.
 * @param int $year
 * @return bool
 */
function isJewishLeapYear(int $year): bool
{
    if ($year % 19 == 0 || $year % 19 == 3 || $year % 19 == 6 ||
        $year % 19 == 8 || $year % 19 == 11 || $year % 19 == 14 ||
        $year % 19 == 17
    ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if a number is a valid month.
 * More params you passed (year, calendar), more accurate is the check.
 * If passed a not valid year return false.
 * @param int $value
 * @param int $year
 * @param int $calendar
 * @return bool
 */
function isMonth(int $value, int $year, int $calendar = CAL_GREGORIAN): bool
{
    if (!isInRange($year, 0, PHP_INT_MAX)) {
        return false;
    }

    $maxMonths = 12;

    if ($calendar == 3
        || ($year > 0 && $calendar == 2 && isJewishLeapYear($year))
    ) {
        $maxMonths = 13;
    }

    return isInRange($value, 1, $maxMonths);
}

/**
 * Check if a number is a valid day.
 * More params you passed (month, year, calendar), more accurate is the check.
 * If passed a not valid year or month return false.
 * @param int $value
 * @param int $month
 * @param int $year
 * @param int $calendar
 * @return bool
 */
function isDay(int $value, int $month = 0, int $year = 0, int $calendar = CAL_GREGORIAN): bool
{
    if ($month != 0 && !isMonth($month, $year, $calendar)) {
        return false;
    }
    if (!isInRange($year, 0, PHP_INT_MAX)) {
        return false;
    }

    $maxDays = 31;

    if ($year > 0 && $month > 0) {
        $maxDays = cal_days_in_month($calendar, $month, $year);
    } elseif (in_array($month, [11, 4, 6, 9])) {
        $maxDays = 30;
    } elseif ($month == 2) {
        $maxDays = 28;
    }

    return isInRange($value, 1, $maxDays);
}

/**
 * Determine if the provided input meets age requirement (ISO 8601).
 *
 * @param string $dateOfBirthday date ('Y-m-d') or datetime ('Y-m-d H:i:s') Date Of Birthday
 * @param int $age
 *
 * @return bool
 */
function hasMinAge($dateOfBirthday, int $age): bool
{
    return date_diff(date('Y-m-d'), $dateOfBirthday) >= $age;
}

/**
 * Determine if the provided input meets age requirement (ISO 8601).
 *
 * @param string $dateOfBirthday date ('Y-m-d') or datetime ('Y-m-d H:i:s') Date Of Birthday
 * @param int $age
 *
 * @return bool
 */
function hasMaxAge($dateOfBirthday, int $age): bool
{
    return date_diff(date('Y-m-d'), $dateOfBirthday) <= $age;
}

/**
 * Determine if the provided input meets age requirement (ISO 8601).
 *
 * @param string $dateOfBirthday date ('Y-m-d') or datetime ('Y-m-d H:i:s') Date Of Birthday
 * @param int $ageMin
 * @param int $ageMax
 *
 * @return bool
 */
function hasAgeInRange($dateOfBirthday, int $ageMin, int $ageMax): bool
{
    return hasMinAge($dateOfBirthday, $ageMin) && hasMaxAge($dateOfBirthday, $ageMax);
}

/**
 * Check if a date in iso format is in range
 * @param string $date iso format
 * @param string $minDate iso format
 * @param string $maxDate iso format
 * @param bool $strict if set to false (default) check >=min and <=max otherwise check >min and <max.
 * @return bool
 */
function betweenDateIso(string $date, string $minDate, string $maxDate, bool $strict = false): bool
{
    if (!isDateIso($date) || !isDateIso($minDate) || !isDateIso($maxDate)) {
        return false;
    }

    if (!$strict) {
        return ($date >= $minDate) && ($date <= $maxDate);
    }
    return ($date > $minDate) && ($date < $maxDate);
}

/**
 * Check if a date in ita format is in range
 * @param string $date ita format
 * @param string $minDate ita format
 * @param string $maxDate ita format
 * @param bool $strict if set to false (default) check >=min and <=max otherwise check >min and <max.
 * @return bool
 */
function betweenDateIta(string $date, string $minDate, string $maxDate, bool $strict = false): bool
{
    if (!isDateIta($date) || !isDateIta($minDate) || !isDateIta($maxDate)) {
        return false;
    }

    $date = dateItaToIso($date);
    $minDate = dateItaToIso($minDate);
    $maxDate = dateItaToIso($maxDate);

    return betweenDateIso($date, $minDate, $maxDate, $strict);
}

/**
 * @param $value
 * @param bool $checkMx
 * @return bool
 */
function isMail($value, bool $checkMx = false): bool
{
    if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
        return false;
    }
    if ($checkMx) {
        list(, $mailDomain) = explode('@', $value);
        if (!checkdnsrr($mailDomain, 'MX')) {
            return false;
        }
    }
    return true;
}

/**
 * isIPv4 check if a string is a valid IP v4
 * @param string $IP2Check IP to check
 * @return bool
 */
function isIPv4($IP2Check): bool
{
    return !(filter_var($IP2Check, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false);
}

/**
 * isIPv6 check if a string is a valid IP v6
 * @param string $IP2Check IP to check
 * @return bool
 */
function isIPv6($IP2Check): bool
{
    return !(filter_var($IP2Check, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false);
}

/**
 * Check if a string is a valid IP (v4 or v6).
 * @param string $IP2Check IP to check
 * @return bool
 */
function isIP($IP2Check): bool
{
    return !(filter_var($IP2Check, FILTER_VALIDATE_IP) === false);
}

/**
 * Check if a string is a valid IP v4 compatibility (ffff:ffff:ffff:ffff.192.168.0.15).
 * @param string $IP2Check IP to check
 * @return bool
 */
function isIPv4Compatibility($IP2Check): bool
{
    return (isNotNullOrEmpty($IP2Check)
        && strrpos($IP2Check, ":") > 0
        && strrpos($IP2Check, ".") > 0
        && isIPv4(substr($IP2Check, strpos($IP2Check, ".") + 1))
        && isIPv6(substr($IP2Check, 0, strpos($IP2Check, ".")) . ':0:0:0:0')
    );
}

/**
 * Check if a string has a URL address syntax is valid.
 * It require scheme to be valide (http|https|ftp|mailto|file|data)
 * i.e.: http://dummy.com and http://www.dummy.com is valid but www.dummy.and dummy.com return false.
 * @param $url
 * @return bool
 */
function isUrl($url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Check if a string is valid hostname
 * (dummy.com, www.dummy.com, , www.dummy.co.uk, , www.dummy-dummy.com, etc..).
 * @param $value
 * @return bool
 */
function isHostname($value): bool
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    return preg_match('/(?=^.{4,253}$)(^((?!-)[a-zA-Z0-9-]{0,62}[a-zA-Z0-9]\.)+[a-zA-Z]{2,63}$)/i', $value) === 1;
}

/**
 * Checks that a value is a valid URL according to http://www.w3.org/Addressing/URL/url-spec.txt
 *
 * The regex checks for the following component parts:
 *
 * - a valid, optional, scheme
 * - a valid ip address OR
 *   a valid domain name as defined by section 2.3.1 of http://www.ietf.org/rfc/rfc1035.txt
 *   with an optional port number
 * - an optional valid path
 * - an optional query string (get parameters)
 * - an optional fragment (anchor tag)
 *
 * @param string $check Value to check
 * @param bool $strict Require URL to be prefixed by a valid scheme (one of http(s)/ftp(s)/file/news/gopher)
 * @return bool Success
 * @see https://github.com/cakephp/cakephp/blob/master/src/Validation/Validation.php#L839
 */
function urlW3c($check, bool $strict = false): bool
{
    if (isNullOrEmpty($check)) {
        return false;
    }
    $_pattern = array();
    $pattern = '((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}';
    $pattern .= '(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})';
    $pattern .= '|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})';
    $pattern .= '(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)';
    $pattern .= '{4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
    $pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}';
    $pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|';
    $pattern .= '((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}';
    $pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
    $pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4})';
    $pattern .= '{0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)';
    $pattern .= '|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]';
    $pattern .= '\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4})';
    $pattern .= '{1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?';
    $_pattern['IPv6'] = $pattern;

    $pattern = '(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])';
    $_pattern['IPv4'] = $pattern;

    $_pattern = ['hostname' => '(?:[_\p{L}0-9][-_\p{L}0-9]*\.)*(?:[\p{L}0-9][-\p{L}0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,})'];

    $validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~[]') . '\/0-9\p{L}\p{N}]|(%[0-9a-f]{2}))';
    $regex = '/^(?:(?:https?|ftps?|sftp|file|news|gopher):\/\/)' . ($strict ? '' : '?') .
        '(?:' . $_pattern['IPv4'] . '|\[' . $_pattern['IPv6'] . '\]|' . $_pattern['hostname'] . ')(?::[1-9][0-9]{0,4})?' .
        '(?:\/?|\/' . $validChars . '*)?' .
        '(?:\?' . $validChars . '*)?' .
        '(?:#' . $validChars . '*)?$/iu';
    return preg_match($regex, $check) === 1;
}

/**
 * Check if a valid EU vat given.
 * @param string $pi required eu vat number with or without country code prefix.
 * If you don't pass country code prefix, 'IT' will be assumed.
 * @param bool $validateOnVIES default false. if se to true, first check formal EU country algorithm,
 * then if it valid and country code isn't 'IT' try to check by API VIES service.
 * If VIES return false or soap exception was thrown, return false.
 * @param bool $returnValueIfViesThrownEx if VIES service thrown an exception, return this value.
 * @return bool
 */
function isEuVatNumber(string $pi, bool $validateOnVIES = false, bool $returnValueIfViesThrownEx = false): bool
{
    $countryCode = getCoutryCodeByVatNumber($pi, 'IT');

    $result = true;
    if (function_exists('is' . $countryCode . 'Vat')) {
        $funcname = 'is' . $countryCode . 'Vat';
        $result = $funcname($pi);
    }
    if (!$result) {
        return false;
    }
    if ($countryCode == 'IT' || !$validateOnVIES) {
        return $result;
    }

    //check vies
    try {
        return isVATRegisteredInVies($pi);
    } catch (\Throwable $e) {
        return $returnValueIfViesThrownEx;
    }
}

/**
 * Try to extract EU country code in Vat number
 * return $fallback if it fails.
 *
 * @param string $pi
 * @param string $fallback
 * @return string
 */
function getCoutryCodeByVatNumber(string $pi, string $fallback = 'IT'): string
{
    if ($pi === null || $pi === '' || strlen($pi) < 2) {
        return $fallback;
    }

    //try to find country code
    $countryCode = strtoupper(substr($pi, 0, 2));
    if (!(preg_match('/^[A-Za-z]{2}$/', $countryCode) === 1)) {
        $countryCode = $fallback;
    }
    return $countryCode;
}

/**
 * Check Italian Vat Number (Partita IVA).
 * @param string $pi Partita IVA Italiana è costituita da 11 cifre o 13 caratteri (prefisso 2 lettere IT).
 * Non sono ammessi caratteri di spazio, per cui i campi di input dell'utente dovrebbero
 * essere trimmati preventivamente.
 * @return bool
 * @version 2012-05-12
 * @author Umberto Salsi <salsi@icosaedro.it>
 * @author Lorenzo Padovani modified.
 */
function isITVat(string $pi): bool
{
    $countryCode = getCoutryCodeByVatNumber($pi, '');
    if ($countryCode != 'IT' && $countryCode != '') {
        return false;
    }
    if ($countryCode != '') {
        $pi = substr($pi, 2);
    }

    if ($pi === null || $pi === '' || strlen($pi) != 11 || preg_match("/^[0-9]+\$/", $pi) != 1) {
        return false;
    }
    $s = 0;
    for ($i = 0; $i <= 9; $i += 2) {
        $s += ord($pi[$i]) - ord('0');
    }
    for ($i = 1; $i <= 9; $i += 2) {
        $c = 2 * (ord($pi[$i]) - ord('0'));
        if ($c > 9) {
            $c -= 9;
        }
        $s += $c;
    }
    if ((10 - $s % 10) % 10 != ord($pi[10]) - ord('0')) {
        return false;
    }

    return true;
}

/**
 * Validate a European VAT number using the EU commission VIES service.
 * To verify if VAT number is authorized to carry out intra-Community operations must use the service
 * If not $vatNumber starts with country code, a default $countryCodeDefault applied.
 * @param string $vatNumber
 * @param string $countryCodeDefault default 'IT'
 * @return bool
 * @throws SoapFault
 */
function isVATRegisteredInVies(string $vatNumber, string $countryCodeDefault = 'IT'): bool
{
    if (!isAlphaNumericWhiteSpaces($vatNumber) || strlen(trim($vatNumber)) < 3) {
        return false;
    }

    $vatNumber = str_replace([' ', '-', '.', ','], '', strtoupper(trim($vatNumber)));
    $countryCode = strtoupper(substr($vatNumber, 0, 2));
    if (isNullOrEmpty($countryCode)) {
        return false;
    }

    if (preg_match('/^[A-Za-z]{2}$/', $countryCode) === 1) {
        $vatNumber = substr($vatNumber, 2);
    } else {
        $countryCode = $countryCodeDefault != '' ? strtoupper($countryCodeDefault) : 'IT';
    }
    try {
        $serviceUrl = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
        $client = new SoapClient($serviceUrl);
        $response = $client->checkVat([
                                          'countryCode' => $countryCode,
                                          'vatNumber' => $vatNumber,
                                      ]);
        return $response->valid;
    } catch (SoapFault $e) {
        throw $e;
    }
}

/**
 * Controlla codice fiscale.
 * @param string $cf Codice fiscale costituito da 16 caratteri. Non
 * sono ammessi caratteri di spazio, per cui i campi di input dell'utente
 * dovrebbero essere trimmati preventivamente. La stringa vuota e' ammessa,
 * cioe' il dato viene considerato opzionale.
 * @return bool
 * @author Umberto Salsi <salsi@icosaedro.it>
 * @version 2012-05-12
 */
function isCf(string $cf): bool
{
    if ($cf === null || $cf === '' || strlen($cf) != 16) {
        return false;
    }
    $cf = strtoupper($cf);
    if (preg_match("/^[A-Z0-9]+\$/", $cf) != 1) {
        return false;
    }
    $s = 0;
    for ($i = 1; $i <= 13; $i += 2) {
        $c = $cf[$i];
        if (strcmp($c, "0") >= 0 && strcmp($c, "9") <= 0) {
            $s += ord($c) - ord('0');
        } else {
            $s += ord($c) - ord('A');
        }
    }
    for ($i = 0; $i <= 14; $i += 2) {
        $c = $cf[$i];
        switch ($c) {
            case '0':
                $s += 1;
                break;
            case '1':
                $s += 0;
                break;
            case '2':
                $s += 5;
                break;
            case '3':
                $s += 7;
                break;
            case '4':
                $s += 9;
                break;
            case '5':
                $s += 13;
                break;
            case '6':
                $s += 15;
                break;
            case '7':
                $s += 17;
                break;
            case '8':
                $s += 19;
                break;
            case '9':
                $s += 21;
                break;
            case 'A':
                $s += 1;
                break;
            case 'B':
                $s += 0;
                break;
            case 'C':
                $s += 5;
                break;
            case 'D':
                $s += 7;
                break;
            case 'E':
                $s += 9;
                break;
            case 'F':
                $s += 13;
                break;
            case 'G':
                $s += 15;
                break;
            case 'H':
                $s += 17;
                break;
            case 'I':
                $s += 19;
                break;
            case 'J':
                $s += 21;
                break;
            case 'K':
                $s += 2;
                break;
            case 'L':
                $s += 4;
                break;
            case 'M':
                $s += 18;
                break;
            case 'N':
                $s += 20;
                break;
            case 'O':
                $s += 11;
                break;
            case 'P':
                $s += 3;
                break;
            case 'Q':
                $s += 6;
                break;
            case 'R':
                $s += 8;
                break;
            case 'S':
                $s += 12;
                break;
            case 'T':
                $s += 14;
                break;
            case 'U':
                $s += 16;
                break;
            case 'V':
                $s += 10;
                break;
            case 'W':
                $s += 22;
                break;
            case 'X':
                $s += 25;
                break;
            case 'Y':
                $s += 24;
                break;
            case 'Z':
                $s += 23;
                break;
            /*. missing_default: .*/
        }
    }
    return !(chr($s % 26 + ord('A')) != $cf[15]);
}

/**
 * Determine if the provided value contains only alpha characters.
 *
 * @param string $field
 *
 * @return mixed
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function isAlpha(string $field): bool
{
    return isNotNullOrEmpty($field)
        && preg_match('/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ])+$/iu', $field) === 1;
}

/**
 * Determine if the provided value contains only alpha characters.
 *
 * @param string $field
 *
 * @return mixed
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function isAlphaNumeric(string $field): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    return preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ])+$/iu', $field) === 1;
}

/**
 * Determine if the provided value contains only numeric characters with or without(default) sign.
 *
 * @param string $field
 * @param bool $acceptSign default false if true accept string that starts with +/- oterwise only [0-9] chars.
 *
 * @return mixed
 */
function isNumeric(string $field, bool $acceptSign = false): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    return preg_match('/^(' . ($acceptSign ? '[+-]{0,1}' : '') . '[0-9])+$/i', $field) === 1;
}

/**
 * Determine if the provided value contains only numeric characters with sign.
 *
 * @param string $field
 *
 * @return mixed
 */
function isNumericWithSign(string $field): bool
{
    return isNumeric($field, true);
}

/**
 * Determine if the provided value contains only numeric characters without sign.
 *
 * @param string $field
 *
 * @return mixed
 */
function isNumericWithoutSign(string $field): bool
{
    return isNumeric($field, false);
}

/**
 * Determine if the provided value contains only alpha characters with dashed and underscores.
 *
 * @param string $field
 *
 * @return mixed
 */
function isAlphaNumericDash($field): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    return preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ\-_])+$/iu', $field) === 1;
}

/**
 * Determine if the provided value contains only alpha numeric characters with spaces.
 *
 * @param string $field
 *
 * @return mixed
 */
function isAlphaNumericWhiteSpaces($field): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    return preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ\-_\s])+$/iu', $field) === 1;
}

/**
 * Determine if the provided value is a boolean.
 *
 * @param string $field
 *
 * @return mixed
 */
function isBool($field): bool
{
    return $field === true || $field === false;
}

/**
 * Determine if the provided value is a boolean or 1,0,'1','0'.
 *
 * @param string $field
 *
 * @return bool
 */
function isBoolOrIntBool($field): bool
{
    return in_array($field, [0, 1, '0', '1', true, false], true);
}

/**
 * Determine if the input is a valid credit card number.
 *
 * See: http://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
 *
 * @param string $field
 *
 * @return mixed
 */
function isCrediCard(string $field): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    $number = preg_replace('/\D/', '', $field);
    if (function_exists('mb_strlen')) {
        $number_length = mb_strlen($number);
    } else {
        $number_length = strlen($number);
    }
    $parity = $number_length % 2;
    $total = 0;
    for ($i = 0; $i < $number_length; ++$i) {
        $digit = $number[$i];
        if ($i % 2 == $parity) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $total += $digit;
    }
    return ($total % 10 == 0);
}

/**
 * Determine if the input is a valid human name.
 *
 * @param string $field
 *
 * @return mixed
 *
 * @See: https://github.com/Wixel/GUMP/issues/5
 */
function isValidHumanName(string $field): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    return isAlpha($field) && preg_match("/^([ '-])+$/", $field) === 1;
}

/**
 * Determine if the provided value is a valid IBAN.
 *
 * @param string $field
 *
 * @return bool
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function isIban($field): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    static $character = array(
        'A' => 10,
        'C' => 12,
        'D' => 13,
        'E' => 14,
        'F' => 15,
        'G' => 16,
        'H' => 17,
        'I' => 18,
        'J' => 19,
        'K' => 20,
        'L' => 21,
        'M' => 22,
        'N' => 23,
        'O' => 24,
        'P' => 25,
        'Q' => 26,
        'R' => 27,
        'S' => 28,
        'T' => 29,
        'U' => 30,
        'V' => 31,
        'W' => 32,
        'X' => 33,
        'Y' => 34,
        'Z' => 35,
        'B' => 11
    );
    if (preg_match('/\A[A-Z]{2}\d{2} ?[A-Z\d]{4}( ?\d{4}){1,} ?\d{1,4}\z/', $field) != 1) {
        return false;
    }
    $iban = str_replace(' ', '', $field);
    $iban = substr($iban, 4) . substr($iban, 0, 4);
    $iban = strtr($iban, $character);
    return (bcmod($iban, 97) != 1);
}

/**
 * check the file extension
 * for now checks onlt the ext should add mime type check.
 *
 * @param string $filePath
 * @param array $allowed_extensions array of extension to match
 *
 * @return bool
 * @see https://github.com/cakephp/cakephp/blob/master/src/Validation/Validation.php
 */
function hasFileExtension($filePath, array $allowed_extensions): bool
{
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $allowed_extensions = (array)array_map('mb_strtolower', $allowed_extensions);
    return in_array($extension, $allowed_extensions);
}

/**
 * Determine if the provided value is a valid phone number.
 *
 * @param string $field
 *
 * @return bool
 *
 * Examples:
 *
 *    555-555-5555: valid
 *    5555425555: valid
 *    555 555 5555: valid
 *    1(519) 555-4444: valid
 *    1 (519) 555-4422: valid
 *    1-555-555-5555: valid
 *    1-(555)-555-5555: valid
 *    +1(519) 555-4444: valid
 *    +1 (519) 555-4422: valid
 *    +1-555-555-5555: valid
 *    +1-(555)-555-5555: valid
 *
 * @see https://github.com/Wixel/GUMP/blob/master/gump.class.php
 */
function isphoneNumber($field): bool
{
    if (isNullOrEmpty($field) || strlen(trim($field)) < 2) {
        return false;
    }
    $field = trim($field);
    if (starts_withEx($field, '+')) {
        $field = trim(substr($field, 1));
    }
    $regex = '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i';
    return preg_match($regex, $field) === 1;
}

/**
 * check is string is a Json string.
 *
 * @param string $field
 *
 * @return bool
 */
function isJsonString($field): bool
{
    if (isNullOrEmpty($field)) {
        return false;
    }
    return is_string($field) && is_object(json_decode($field));
}


/**
 * Checks that a value is a valid UUID - http://tools.ietf.org/html/rfc4122
 *
 * @param string $check Value to check
 * @return bool Success
 */
function isUuid($check)
{
    if (isNullOrEmpty($check)) {
        return false;
    }
    $regex = '/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/';
    return preg_match($regex, $check) === 1;
}


/**
 * Validates a geographic coordinate.
 *
 * Supported formats:
 *
 * - `<latitude>, <longitude>` Example: `-25.274398, 133.775136`
 *
 * ### Options
 *
 * - `type` - A string of the coordinate format, right now only `latLong`.
 * - `format` - By default `both`, can be `long` and `lat` as well to validate
 *   only a part of the coordinate.
 *
 * @param string $value Geographic location as string
 * @param array $options Options for the validation logic.
 * @return bool|Exception
 */
function isGeoCoordinate($value, array $options = [])
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    $_pattern = [
        'latitude' => '[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)',
        'longitude' => '[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)',
    ];

    $options += [
        'format' => 'both',
        'type' => 'latLong'
    ];
    if ($options['type'] !== 'latLong') {
        throw new RuntimeException(sprintf(
                                       'Unsupported coordinate type "%s". Use "latLong" instead.',
                                       $options['type']
                                   ));
    }
    $pattern = '/^' . $_pattern['latitude'] . ',\s*' . $_pattern['longitude'] . '$/';
    if ($options['format'] === 'long') {
        $pattern = '/^' . $_pattern['longitude'] . '$/';
    }
    if ($options['format'] === 'lat') {
        $pattern = '/^' . $_pattern['latitude'] . '$/';
    }
    return (bool)preg_match($pattern, $value);
}

/**
 * Convenience method for latitude validation.
 *
 * @param string $value Latitude as string
 * @param array $options Options for the validation logic.
 * @return bool
 * @link https://en.wikipedia.org/wiki/Latitude
 * @see \Cake\Validation\Validation::geoCoordinate()
 */
function isLatitude($value, array $options = [])
{
    $options['format'] = 'lat';
    return isGeoCoordinate($value, $options);
}

/**
 * Convenience method for longitude validation.
 *
 * @param string $value Latitude as string
 * @param array $options Options for the validation logic.
 * @return bool
 * @link https://en.wikipedia.org/wiki/Longitude
 * @see \Cake\Validation\Validation::geoCoordinate()
 */
function isLongitude($value, array $options = []): bool
{
    $options['format'] = 'long';
    return isGeoCoordinate($value, $options);
}

/**
 * Check that the input value is within the ascii byte range.
 *
 * This method will reject all non-string values.
 *
 * @param string $value The value to check
 * @return bool
 */
function isAscii($value)
{
    if (!is_string($value)) {
        return false;
    }
    return strlen($value) <= mb_strlen($value, 'utf-8');
}

/**
 * Check that the input value is a utf8 string.
 *
 * This method will reject all non-string values.
 *
 * # Options
 *
 * - `extended` - Disallow bytes higher within the basic multilingual plane.
 *   MySQL's older utf8 encoding type does not allow characters above
 *   the basic multilingual plane. Defaults to false.
 *
 * @param string $value The value to check
 * @param array $options An array of options. See above for the supported options.
 * @return bool
 */
function isUtf8($value, array $options = []): bool
{
    if (isNullOrEmpty($value)) {
        return false;
    }
    if (!is_string($value)) {
        return false;
    }
    $options += ['extended' => false];
    if ($options['extended']) {
        return true;
    }
    return preg_match('/[\x{10000}-\x{10FFFF}]/u', $value) === 0;
}
