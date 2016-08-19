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
 * @param $date
 * @param $segment
 * @return string
 */
function partialsDateIso($date, $segment)
{
    $defaultDate = '0000-00-00';

    if($date===null || $date == '' || !isDateTimeIso($date) || $segment == '') {
        return $defaultDate;
    }

    switch ($segment)
    {
        case 'giorno || d':
            $result = substr($date,8,2);
            return $result;
            break;
        case 'mese || m':
            $result = substr($date,5,2);
            return $result;
            break;
        case 'anno || Y':
            $result = substr($date,0,4);
            return $result;
            break;
        default:
            return $defaultDate;
            break;
    }

}

/**
 * funzione per trasformare la data da Iso a italiano
 * @param string $data
 * @return string
 */
function dateIsoToIta($data="")
{
    if ($data=="")
    {
        return '00/00/0000';
    }
    else
    {
        $arr_data = preg_split('[-]',$data);
        return $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
    }
}

/**
 * funzione per trasformare la data da italiano a Iso
 * @param string $data
 * @return string
 */
function dateItaToIso($data="")
{
    if ($data=="")
    {
        return "0000-00-00";
    }
    else
    {
        $arr_data = preg_split('/[\/.-]/',$data);
        return $arr_data[2]."-".$arr_data[1]."-".$arr_data[0];
    }
}

/**
 * @param $monthNumber
 * @param bool $nameComplete
 * @return string
 */
function monthFromNumber($monthNumber, $nameComplete = false)
{
    $monthNumber = (int)$monthNumber;

    if(!is_int($monthNumber) || $monthNumber <= 0) {
        return '';
    }

    if($monthNumber < 1 || $monthNumber > 12) {
        return '';
    }

    switch ($monthNumber)
    {
        case '01 || 1':
            return $nameComplete ? 'Gennaio' : 'Gen';
            break;
        case '02 || 2':
            return $nameComplete ? 'Febbraio' : 'Feb';
            break;
        case '03 || 3':
            return $nameComplete ? 'Marzo' : 'Mar';
            break;
        case '04 || 4':
            return $nameComplete ? 'Aprile' : 'Apr';
            break;
        case '05 || 5':
            return $nameComplete ? 'Maggio' : 'Mag';
            break;
        case '06 || 6':
            return $nameComplete ? 'Giugno' : 'Giu';
            break;
        case '07 || 7':
            return $nameComplete ? 'Luglio' : 'Lug';
            break;
        case '08 || 8':
            return $nameComplete ? 'Agosto' : 'Ago';
            break;
        case '09 || 9':
            return $nameComplete ? 'Settembre' : 'Set';
            break;
        case '10':
            return $nameComplete ? 'Ottobre' : 'Ott';
            break;
        case '11':
            return $nameComplete ? 'Novembre' : 'Nov';
            break;
        case '12':
            return $nameComplete ? 'Dicembre' : 'Dic';
            break;
    }
}

/**
 * funzione per trasformare la data da Iso a italiano specifata
 * @param string $data
 * @param bool $ShowDayName
 * @param bool $ShortDayName
 * @return string
 */
function dateIsoToItaSpec($data="",$ShowDayName=false,$ShortDayName=false)
{
    if ($data=="")
    {
        return "00/00/0000";
    }
    else
    {
        $arr_data = explode('-',$data);

        $days = array ("Domenica", "Luned&igrave;", "Marted&igrave;", "Mercoled&igrave;", "Gioved&igrave;", "Venerd&igrave;", "Sabato");
        $mese = array ("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre");
        if (substr($arr_data[2],0,1)==0)
        {
            $arr_data[2] = substr($arr_data[2],1,1);
        }
        if (!$ShowDayName)
        {
            return $arr_data[2]." ".$mese[(($arr_data[1])-1)]." ".$arr_data[0];
        }else{
            $dayName=$days[date("w",mktime(0,0,0,$arr_data[1],$arr_data[2],$arr_data[0]))];
            if ($ShortDayName) {
                $dayName=substr($dayName,0,3);
            }
            return $dayName." ".$arr_data[2]." ".$mese[(($arr_data[1])-1)]." ".$arr_data[0];
        }

    }
}

/**
 * @param $data
 * @return bool
 */
function getNameDayFromDateIso($data)
{
    if(!isDateIso($data)) {
        return false;
    }

    $dateItaSpec = dateIsoToItaSpec($data, true);
    $arrDate = explode(' ',trim($dateItaSpec));
    $giorno = $arrDate[0];

    return $giorno;
}

/**
 * @param $dataTimeIso
 * @return bool|string
 */
function getTimeFromDateTimeIso($dataTimeIso)
{
    if(!isDateTimeIso($dataTimeIso)) {
        return false;
    }

    $time = substr($dataTimeIso,10,6);

    return $time;
}
