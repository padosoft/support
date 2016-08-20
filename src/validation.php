<?php

/**
 * Check if a string number starts with one ore more zero
 * i.e.: 00...000 or 000...0Xxxx.x  with X an int
 * @param $value
 * @return bool
 */
function isStringNumberStartsWithMoreThanOneZero($value)
{
    return preg_match('/^[0]{2,}$/', $value) === 1 || preg_match('/^0{1,}[1-9]{1,}$/', $value) === 1;
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
function isInteger($value, $unsigned = true, $acceptIntegerFloatingPoints = false) : bool
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
function isIntegerFloatingPoint($value, $unsigned = true) : bool
{
    return isFloatingPoint($value, $unsigned)
    && $value <= PHP_INT_MAX && $value >= PHP_INT_MIN
    //big number rouned to int aproximately!
    //big number change into exp format
    && ((int)((double)$value) == $value || (int)$value == $value || strpos(strtoupper((string)$value), 'E') === false);
}

/**
 * Check if string is a valid floating point.
 * Ex.: 1, 1e2, 1E2, 1e+2, 1e-2, 1.43234e+2, -1.231e+2, -1.231e-2 etc...
 * @param $value
 * @param $unsigned
 * @return bool
 */
function isFloatingPoint($value, $unsigned) : bool
{
    if (isStringNumberStartsWithMoreThanOneZero($value)) {
        return false;
    }

    return preg_match('/^' . ($unsigned ? '' : '-{0,1}') . '[0-9]{1,}(\.[0-9]{1,}){0,1}([Ee][+,-]{0,1}[0-9]{1,}){0,}$/',
        $value) === 1;
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
function isDouble($value, $dec = 2, $unsigned = true, $exactDec = false) : bool
{
    if (isStringNumberStartsWithMoreThanOneZero($value)) {
        return false;
    }
    $regEx = '/^' . ($unsigned ? '' : '-{0,1}') . '[0-9]{1,}(\.{1}[0-9]{' . ($exactDec ? '' : '1,') . $dec . '})' . ($exactDec ? '{1}' : '{0,1}') . '$/';
    return preg_match($regEx, $value) === 1;
}

/**
 * Check if string is dd/mm/YYYY
 * @param $value
 * @return bool
 */
function isDateIta($value) : bool
{
    if ($value === null || $value == '' || strlen($value) != 10 || strpos($value, '/') === false) {
        return false;
    }
    list($dd, $mm, $yyyy) = explode('/', $value);
    try {
        return checkdate($mm, $dd, $yyyy);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if string is YYYY-mm-dd
 * @param $value
 * @return bool
 */
function isDateIso($value) : bool
{
    if ($value === null || $value == '' || strlen($value) != 10 || strpos($value, '-') === false) {
        return false;
    }
    list($yyyy, $mm, $dd) = explode('-', $value);
    try {
        return checkdate($mm, $dd, $yyyy);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if string is YYYY-mm-dd HH:ii:ss
 * @param $value
 * @return bool
 */
function isDateTimeIso($value) : bool
{
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
function isDateTimeIta($value) : bool
{
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
function isTimeIso($value) : bool
{
    $strRegExp = '/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/';
    return preg_match($strRegExp, $value) === 1;
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
 * @param $value
 * @return bool
 */
function isMail($value) : bool
{
    return !(filter_var($value, FILTER_VALIDATE_EMAIL) === false);
}

/**
 * isIPv4 check if is a valid IP v4
 * @param  string $IP2Check IP to check
 * @return bool
 */
function isIPv4($IP2Check) : bool
{
    return !(filter_var($IP2Check, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false);
}

/**
 * Simple Check if a URL address syntax is valid (this regular expression also allows dashes in the URL).
 * Do not support ful URI and allow only popular scheme (http|https|ftp|mailto|file|data).
 * Require scheme protocol or www. i.e.: http://dummy.com and www.dummy.com return true but dummy.com return false.
 * @param $url
 * @return bool
 */
function isUrl($url) : bool
{
    if (preg_match('/^(https?|ftp|mailto|file|data):\/\/\./i', $url) === 1) {
        return false;
    }
    return preg_match('/\b(?:(?:https?|ftp|mailto|file|data):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
        $url) === 1;
}

/**
 * Controlla partita IVA.
 * @author Umberto Salsi <salsi@icosaedro.it>
 * @version 2012-05-12
 * @param string $pi Partita IVA costituita da 11 cifre. Non sono ammessi
 * caratteri di spazio, per cui i campi di input dell'utente dovrebbero
 * essere trimmati preventivamente. La stringa vuota e' ammessa, cioe'
 * il dato viene considerato opzionale.
 * @return bool
 */
function isPiva(string $pi) : bool
{
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
    return !((10 - $s % 10) % 10 != ord($pi[10]) - ord('0'));
}

/**
 * Controlla codice fiscale.
 * @author Umberto Salsi <salsi@icosaedro.it>
 * @version 2012-05-12
 * @param string $cf Codice fiscale costituito da 16 caratteri. Non
 * sono ammessi caratteri di spazio, per cui i campi di input dell'utente
 * dovrebbero essere trimmati preventivamente. La stringa vuota e' ammessa,
 * cioe' il dato viene considerato opzionale.
 * @return bool
 */
function isCf(string $cf) : bool
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
