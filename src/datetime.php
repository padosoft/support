<?php
/**
 * **************************
 * COSTANTS
 * **************************
 */

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
    if (isNullOrEmpty($date) || !isDateIso($date)) {
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
