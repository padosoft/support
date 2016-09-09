<?php
/**
 * **************************
 * COSTANTS
 * **************************
 */

if (!defined('CAL_GREGORIAN')) {
    define('CAL_GREGORIAN', 0);
}
if (!defined('CAL_JULIAN')) {
    define('CAL_JULIAN', 1);
}
if (!defined('CAL_JEWISH')) {
    define('CAL_JEWISH', 2);
}
if (!defined('CAL_FRENCH')) {
    define('CAL_FRENCH', 3);
}

/**
 * SECONDS SHART CUTS
 */
const SECOND_IN_SECOND = 1;
const MINUTE_IN_SECOND = 60;
const HOUR_IN_SECOND = 3600;
const DAY_IN_SECOND = 86400;
const WEEK_IN_SECOND = 604800;
const MONTH_IN_SECOND = 2592000;
const YEAR_IN_SECOND = 31557600;

/**
 * DATE TIME FORMAT
 */
const DATE_TIME_FORMAT_ISO = 'Y-m-d H:i:s';
const DATE_TIME_FORMAT_ITA = 'd/m/Y H:i:s';
const DATE_FORMAT_ISO = 'Y-m-d';
const DATE_FORMAT_ITA = 'd/m/Y';
const TIME_FORMAT_ISO = 'H:i:s';
const TIME_FORMAT_ITA = 'H:i:s';
/**
 * The day constants.
 */
const SUNDAY = 0;
const MONDAY = 1;
const TUESDAY = 2;
const WEDNESDAY = 3;
const THURSDAY = 4;
const FRIDAY = 5;
const SATURDAY = 6;

const DAYS_ITA_ARR = [
    0 => 'Domenica',
    1 => 'lunedi',
    2 => 'martedi',
    3 => 'mercoledi',
    4 => 'giovedi',
    5 => 'venerdi',
    6 => 'sabato',
];

const DAYS_ENG_ARR = [
    0 => 'Sunday',
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
];

/**
 * The month constants.
 */
const GENNAIO = 0;
const FEBBRAIO = 1;
const MARZO = 2;
const APRILE = 3;
const MAGGIO = 4;
const GIUGNO = 5;
const LUGLIO = 6;
const AGOSTO = 7;
const SETTEMBRE = 8;
const OTTOBRE = 9;
const NOVEMBRE = 10;
const DICEMBRE = 11;

const MONTHS_ITA_ARR = [
    0 => 'Gennaio',
    1 => 'Febbraio',
    2 => 'Marzo',
    3 => 'Aprile',
    4 => 'Maggio',
    5 => 'Giugno',
    6 => 'Luglio',
    7 => 'Agosto',
    8 => 'Settembre',
    9 => 'Ottobre',
    10 => 'Novembre',
    11 => 'Dicembre',
];

const MONTHS_ITA_ARR_1_BASED = [
    1 => 'Gennaio',
    2 => 'Febbraio',
    3 => 'Marzo',
    4 => 'Aprile',
    5 => 'Maggio',
    6 => 'Giugno',
    7 => 'Luglio',
    8 => 'Agosto',
    9 => 'Settembre',
    10 => 'Ottobre',
    11 => 'Novembre',
    12 => 'Dicembre',
];

const MONTHS_SHORT_ITA_ARR = [
    0 => 'Gen',
    1 => 'Feb',
    2 => 'Mar',
    3 => 'Apr',
    4 => 'Mag',
    5 => 'Giu',
    6 => 'Lug',
    7 => 'Ago',
    8 => 'Set',
    9 => 'Ott',
    10 => 'Nov',
    11 => 'Dic',
];

const MONTHS_SHORT_ITA_ARR_1_BASED = [
    1 => 'Gen',
    2 => 'Feb',
    3 => 'Mar',
    4 => 'Apr',
    5 => 'Mag',
    6 => 'Giu',
    7 => 'Lug',
    8 => 'Ago',
    9 => 'Set',
    10 => 'Ott',
    11 => 'Nov',
    12 => 'Dic',
];

/**
 * **************************
 * HELPERS
 * **************************
 */

/**
 * Get Carbon istance by string $date in 'Y-m-d H:i:s' format.
 * @param string $date
 * @return \Carbon\Carbon
 */
function carbonFromIsoDateTime(string $date): Carbon\Carbon
{
    return Carbon\Carbon::createFromFormat(DATE_TIME_FORMAT_ISO, $date);
}

/**
 * Get Carbon istance by string $date in 'Y-m-d' format.
 * @param string $date
 * @return \Carbon\Carbon
 */
function carbonFromIsoDate(string $date): Carbon\Carbon
{
    return Carbon\Carbon::createFromFormat(DATE_FORMAT_ISO, $date);
}

/**
 * Get Carbon istance by string $date in 'd/m/Y H:i:s' format.
 * @param string $date
 * @return \Carbon\Carbon
 */
function carbonFromItaDateTime(string $date): Carbon\Carbon
{
    return Carbon\Carbon::createFromFormat(DATE_TIME_FORMAT_ITA, $date);
}

/**
 * Get Carbon istance by string $date in 'd/m/Y' format.
 * @param string $date
 * @return \Carbon\Carbon
 */
function carbonFromItaDate(string $date): Carbon\Carbon
{
    return Carbon\Carbon::createFromFormat(DATE_FORMAT_ITA, $date);
}

/**
 * Get Carbon istance by string $date in $format format.
 * If $date string doesn't match passed forma, return Carbon::create().
 * @param string $date
 * @param string $format
 * @return \Carbon\Carbon
 */
function carbon(string $date, string $format = DATE_TIME_FORMAT_ISO): Carbon\Carbon
{
    return Carbon\Carbon::createFromFormat($format, $date);
}

/**
 * int to roman number
 * @param int|null $year
 * @return string
 * @see https://github.com/spatie-custom/blender/blob/master/app/Foundation/helpers.php
 */
function roman_year(int $year = null): string
{
    if (!is_numeric($year)) {
        $year = (int)date('Y');
    }

    $result = '';

    $romanNumerals = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    ];

    foreach ($romanNumerals as $roman => $yearNumber) {
        // Divide to get  matches
        $matches = (int)($year / $yearNumber);

        // Assign the roman char * $matches
        $result .= str_repeat($roman, $matches);

        // Substract from the number
        $year = $year % $yearNumber;
    }

    return $result;
}

/**
 * Split date iso
 * If $date is not valid set to '0000-00-00'.
 * If $segment is not valid set to 'y'.
 * @param string $date
 * @param string $segment allowed value: d, giorno, m, mese, Y, anno
 * @return string
 */
function partialsDateIso(string $date, string $segment = 'Y') : string
{
    if (isNullOrEmpty($segment)) {
        $segment = 'Y';
    }

    if (isNullOrEmpty($date) || (!isDateIso($date) && !isDateTimeIso($date))) {
        $date = '0000-00-00';
    }

    switch ($segment) {
        case 'giorno || d':
            $result = substr($date, 8, 2);
            break;
        case 'mese || m':
            $result = substr($date, 5, 2);
            break;
        case 'anno || Y':
        default:
            $result = substr($date, 0, 4);
            break;
    }

    return $result;
}

/**
 * Funzione per trasformare la data da Iso a italiano
 * If date is invalid return '00/00/0000'.
 * @param string $date
 * @return string
 */
function dateIsoToIta(string $date = "") : string
{
    if (isNullOrEmpty($date) || !isDateIso($date)) {
        return '00/00/0000';
    }
    $arr_data = preg_split('[-]', $date);
    return $arr_data[2] . "/" . $arr_data[1] . "/" . $arr_data[0];
}

/**
 * funzione per trasformare la data da italiano a Iso
 * If date is invalid return '0000-00-00'.
 * @param string $date
 * @return string
 */
function dateItaToIso(string $date = "") : string
{
    if (isNullOrEmpty($date) || !isDateIta($date)) {
        return '0000-00-00';
    }
    $arr_data = preg_split('/[\/.-]/', $date);
    return $arr_data[2] . '-' . $arr_data[1] . '-' . $arr_data[0];
}

/**
 * Return month name by number.
 * If $monthNumber if out of range, return empty string.
 * @param $monthNumber
 * @param bool $nameComplete if se to false (default) return the short form (Gen, Feb,...).
 * @return string
 */
function monthFromNumber(int $monthNumber, bool $nameComplete = false) : string
{
    if ($monthNumber < 1 || $monthNumber > 12) {
        return '';
    }

    return $nameComplete ? MONTHS_ITA_ARR_1_BASED[$monthNumber] : MONTHS_SHORT_ITA_ARR_1_BASED[$monthNumber];
}

/**
 * Funzione per trasformare la data da Iso a italiano specifata
 * @param string $data
 * @param bool $ShowDayName
 * @param bool $ShortDayName
 * @return string
 */
function dateIsoToItaSpec(string $data = "", bool $ShowDayName = false, bool $ShortDayName = false) : string
{
    if (isNullOrEmpty($data) || !isDateIso($data)) {
        return "00/00/0000";
    } else {
        $arr_data = explode('-', $data);

        if (substr($arr_data[2], 0, 1) == 0) {
            $arr_data[2] = substr($arr_data[2], 1, 1);
        }
        if (!$ShowDayName) {
            return $arr_data[2] . " " . MONTHS_ITA_ARR[$arr_data[1] - 1] . " " . $arr_data[0];
        } else {
            $dayName = DAYS_ITA_ARR[(int)date("w", mktime(0, 0, 0, $arr_data[1], $arr_data[2], $arr_data[0]))];
            return ($ShortDayName ? substr($dayName, 0,
                3) : $dayName) . ' ' . $arr_data[2] . ' ' . MONTHS_ITA_ARR[$arr_data[1] - 1] . ' ' . $arr_data[0];
        }
    }
}

/**
 * @param string $data
 * @return string
 */
function getNameDayFromDateIso(string $data) : string
{
    if (!isDateIso($data)) {
        return '';
    }

    $dateItaSpec = dateIsoToItaSpec($data, true);
    $arrDate = explode(' ', trim($dateItaSpec));
    $giorno = $arrDate[0];

    return $giorno;
}

/**
 * @param string $dataTimeIso
 * @return string
 */
function getTimeFromDateTimeIso(string $dataTimeIso) : string
{
    if (!isDateTimeIso($dataTimeIso)) {
        return '00:00';
    }

    return substr($dataTimeIso, 10, 6);
}

/**
 * Determine difference in year between two date.
 *
 * @param string $data1 date ('Y-m-d') or datetime ('Y-m-d H:i:s')
 * @param string $data2 date ('Y-m-d') or datetime ('Y-m-d H:i:s')
 *
 * @return int
 */
function diff_in_year($data1, $data2 = '') : int
{
    if (isNullOrEmpty($data1)) {
        return 0;
    }
    if (isNullOrEmpty($data2)) {
        $data2 = date('Y-m-d');
    }

    $cdate1 = new DateTime(date('Y-m-d', strtotime($data1)));
    $interval = $cdate1->diff($data2);
    return $interval->y;
}

/**
 * Determine the age by date Of Birthday.
 *
 * @param string $dateOfBirthday date ('Y-m-d') or datetime ('Y-m-d H:i:s') Date Of Birthday
 *
 * @return int
 */
function age($dateOfBirthday) : int
{
    return date_diff(date('Y-m-d'), $dateOfBirthday);
}

/**
 * Returns AM or PM, based on a given hour (in 24 hour format).
 *
 *     $type = Date::ampm(12); // PM
 *     $type = Date::ampm(1);  // AM
 *
 * @param   integer $hour number of the hour
 * @return  string
 * @see https://github.com/kohana/ohanzee-helpers/blob/master/src/Date.php
 */
function ampm($hour)
{
    // Always integer
    $hour = (int)$hour;
    return ($hour > 11) ? 'PM' : 'AM';
}

/**
 * Adjusts a non-24-hour number into a 24-hour number.
 *
 *     $hour = Date::adjust(3, 'pm'); // 15
 *
 * @param   integer $hour hour to adjust
 * @param   string $ampm AM or PM
 * @return  string
 * @see https://github.com/kohana/ohanzee-helpers/blob/master/src/Date.php
 */
function ampm2Number($hour, $ampm)
{
    $hour = (int)$hour;
    $ampm = strtolower($ampm);
    switch ($ampm) {
        case 'am':
            if ($hour == 12) {
                $hour = 0;
            }
            break;
        case 'pm':
            if ($hour < 12) {
                $hour += 12;
            }
            break;
    }
    return sprintf('%02d', $hour);
}

/**
 * Returns the difference between a time and now in a "fuzzy" way.
 * Displaying a fuzzy time instead of a date is usually faster to read and understand.
 *
 *     $span = Date::fuzzy_span(time() - 10); // "moments ago"
 *     $span = Date::fuzzy_span(time() + 20); // "in moments"
 *
 * A second parameter is available to manually set the "local" timestamp,
 * however this parameter shouldn't be needed in normal usage and is only
 * included for unit tests
 *
 * @param   integer $timestamp "remote" timestamp
 * @param   integer $local_timestamp "local" timestamp, defaults to time()
 * @return  string $locale default 'IT' otherwise 'EN'
 * @return  string
 * @see https://github.com/kohana/ohanzee-helpers/blob/master/src/Date.php
 */
function fuzzySpan($timestamp, $local_timestamp = null, $locale = 'IT')
{
    $local_timestamp = ($local_timestamp === null) ? time() : (int)$local_timestamp;
    // Determine the difference in seconds
    $offset = abs($local_timestamp - $timestamp);
    if ($offset <= MINUTE_IN_SECOND) {
        $span = $locale == 'EN' ? 'moments' : 'attimi';
    } elseif ($offset < (MINUTE_IN_SECOND * 20)) {
        $span = $locale == 'EN' ? 'a few minutes' : 'qualche minuto';
    } elseif ($offset < HOUR_IN_SECOND) {
        $span = $locale == 'EN' ? 'less than an hour' : 'meno di un ora';
    } elseif ($offset < (HOUR_IN_SECOND * 4)) {
        $span = $locale == 'EN' ? 'a couple of hours' : 'un paio di ore';
    } elseif ($offset < DAY_IN_SECOND) {
        $span = $locale == 'EN' ? 'less than a day' : 'meno di un giorno';
    } elseif ($offset < (DAY_IN_SECOND * 2)) {
        $span = $locale == 'EN' ? 'about a day' : 'circa un giorno';
    } elseif ($offset < (DAY_IN_SECOND * 4)) {
        $span = $locale == 'EN' ? 'a couple of days' : 'un paio di giorni';
    } elseif ($offset < WEEK_IN_SECOND) {
        $span = $locale == 'EN' ? 'less than a week' : 'meno di una settimana';
    } elseif ($offset < (WEEK_IN_SECOND * 2)) {
        $span = $locale == 'EN' ? 'about a week' : 'circa una settimana';
    } elseif ($offset < MONTH_IN_SECOND) {
        $span = $locale == 'EN' ? 'less than a month' : 'meno di un mese';
    } elseif ($offset < (MONTH_IN_SECOND * 2)) {
        $span = $locale == 'EN' ? 'about a month' : 'circa un mese';
    } elseif ($offset < (MONTH_IN_SECOND * 4)) {
        $span = $locale == 'EN' ? 'a couple of months' : 'un paio di mesi';
    } elseif ($offset < YEAR_IN_SECOND) {
        $span = $locale == 'EN' ? 'less than a year' : 'meno di un anno';
    } elseif ($offset < (YEAR_IN_SECOND * 2)) {
        $span = $locale == 'EN' ? 'about a year' : 'circa un anno';
    } elseif ($offset < (YEAR_IN_SECOND * 4)) {
        $span = $locale == 'EN' ? 'a couple of years' : 'un paio di anni';
    } elseif ($offset < (YEAR_IN_SECOND * 8)) {
        $span = $locale == 'EN' ? 'a few years' : 'qualche anno';
    } elseif ($offset < (YEAR_IN_SECOND * 12)) {
        $span = $locale == 'EN' ? 'about a decade' : 'circa un decennio';
    } elseif ($offset < (YEAR_IN_SECOND * 24)) {
        $span = $locale == 'EN' ? 'a couple of decades' : 'una coppia di decenni';
    } elseif ($offset < (YEAR_IN_SECOND * 64)) {
        $span = $locale == 'EN' ? 'several decades' : 'diversi decenni';
    } else {
        $span = $locale == 'EN' ? 'a long time' : 'un lungo periodo';
    }
    if ($timestamp <= $local_timestamp) {
        // This is in the past
        return $span . ($locale == 'EN' ? ' ago' : ' fà');
    } else {
        // This in the future
        return ($locale == 'EN' ? 'in ' : 'fra ') . $span;
    }
}

/**
 * Converts a UNIX timestamp to DOS format. There are very few cases where
 * this is needed, but some binary formats use it (eg: zip files.)
 * Converting the other direction is done using {@link Date::dos2unix}.
 *
 *     $dos = Date::unix2dos($unix);
 *
 * @param   integer $timestamp UNIX timestamp
 * @return  integer
 * @see https://github.com/kohana/ohanzee-helpers/blob/master/src/Date.php
 */
function unixTimestamp2dos($timestamp = null)
{
    $timestamp = getdate($timestamp);
    if ($timestamp['year'] < 1980) {
        return (1 << 21 | 1 << 16);
    }
    $timestamp['year'] -= 1980;
    // What voodoo is this? I have no idea... Geert can explain it though,
    // and that's good enough for me.
    return ($timestamp['year'] << 25 | $timestamp['mon'] << 21 |
        $timestamp['mday'] << 16 | $timestamp['hours'] << 11 |
        $timestamp['minutes'] << 5 | $timestamp['seconds'] >> 1);
}

/**
 * Converts a DOS timestamp to UNIX format.There are very few cases where
 * this is needed, but some binary formats use it (eg: zip files.)
 * Converting the other direction is done using {@link Date::unix2dos}.
 *
 *     $unix = Date::dos2unix($dos);
 *
 * @param  integer|bool $timestamp DOS timestamp
 * @return  integer
 * @see https://github.com/kohana/ohanzee-helpers/blob/master/src/Date.php
 */
function dos2unixTimestamp($timestamp = false)
{
    $sec = 2 * ($timestamp & 0x1f);
    $min = ($timestamp >> 5) & 0x3f;
    $hrs = ($timestamp >> 11) & 0x1f;
    $day = ($timestamp >> 16) & 0x1f;
    $mon = ($timestamp >> 21) & 0x0f;
    $year = ($timestamp >> 25) & 0x7f;
    return mktime($hrs, $min, $sec, $mon, $day, $year + 1980);
}

if (!function_exists('cal_days_in_month')) {
    /**
     * Return the number of days in a month for a given year and calendar
     * If cal_days_in_month() is not defined return the number of days
     * in a month for a given year in CAL_GREGORIAN calendar.
     * If an error occourred return 0.
     * @param $calendar
     * @param int $month
     * @param int $year
     * @return int
     */
    function cal_days_in_month($calendar = CAL_GREGORIAN, $month, $year) : int
    {
        if (!isInRange($month, 1, 12) || $year < 1) {
            return 0;
        }
        $dim = date('t', mktime(0, 0, 0, $month, 1, $year));
        return is_int($dim) ? (int)$dim : 0;
    }
}

if (!function_exists('cal_days_in_current_month')) {
    /**
     * Return the number of days in a current month and calendar
     * If cal_days_in_month() is not defined return the number of days
     * in a current month in CAL_GREGORIAN calendar.
     * If $calendar is null or empty or not is integer, use CAL_GREGORIAN.
     * @param $calendar
     * @return int
     */
    function cal_days_in_current_month(int $calendar = CAL_GREGORIAN) : int
    {
        if (function_exists('cal_days_in_month')) {
            return (int)cal_days_in_month($calendar, date('m'), date('Y'));
        }

        return (int)date('t');
    }
}

if (!function_exists('days_in_month')) {
    /**
     * Return the number of days for a given month and year (using CAL_GREGORIAN calendar).
     * @param int $month
     * @param int $year
     * @return int
     */
    function days_in_month(int $month, int $year) : int
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
}

if (!function_exists('days_in_current_month')) {
    /**
     * Return the number of days in a current month (using CAL_GREGORIAN calendar).
     * @return int
     */
    function days_in_current_month() : int
    {
        return cal_days_in_current_month(CAL_GREGORIAN);
    }
}

