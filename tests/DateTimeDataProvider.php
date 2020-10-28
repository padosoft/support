<?php

namespace Padosoft\Support\Test;


use PHPUnit\Framework\Error\Error;
use \PHPUnit\Framework\Error\Warning;

trait DateTimeDataProvider
{
    /**
     * @return array
     */
    public function cal_days_in_monthProvider()
    {
        return [
            'null, null, null' => [null, null, null, Warning::class],
            '\'\', \'\', \'\'' => ['', '', '',  Error::class],
            '\' \', \' \', \' \' ' => [' ', ' ', ' ',  Error::class],
            '0, 1, 2016' => [0, 1, 2016, 31],
            'CAL_GREGORIAN, 1, 2016' => [CAL_GREGORIAN, 1, 2016, 31],
            'CAL_GREGORIAN, 2, 2015' => [CAL_GREGORIAN, 2, 2015, 28],
            'CAL_GREGORIAN, 2, 2016' => [CAL_GREGORIAN, 2, 2016, 29],
            'CAL_GREGORIAN, 4, 2016' => [CAL_GREGORIAN, 4, 2016, 30],
            'CAL_GREGORIAN, 4, 1900' => [CAL_GREGORIAN, 4, 1900, 30],
            'CAL_GREGORIAN, 4, 2400' => [CAL_GREGORIAN, 4, 2400, 30],
            'CAL_JULIAN, 1, 2016' => [CAL_JULIAN, 1, 2016, 31],
            'CAL_JULIAN, 2, 2015' => [CAL_JULIAN, 2, 2015, 28],
            'CAL_JULIAN, 2, 2016' => [CAL_JULIAN, 2, 2016, 29],
            'CAL_JULIAN, 4, 2016' => [CAL_JULIAN, 4, 2016, 30],
            'CAL_JULIAN, 4, 1900' => [CAL_JULIAN, 4, 1900, 30],
            'CAL_JULIAN, 4, 2400' => [CAL_JULIAN, 4, 2400, 30],
        ];
    }

    /**
     * @return array
     */
    public function cal_days_in_current_monthProvider()
    {
        return [
            'null, '.cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) => [null, 'TypeError'],
            '\'\', '.cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) => ['', 'TypeError'],
            '\' \', '.cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) => [' ', 'TypeError'],
            '0' => [0, cal_days_in_month(0, date('m'), date('Y'))],
            'CAL_GREGORIAN' => [CAL_GREGORIAN, cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'))],
            'CAL_JULIAN, 1, 2016' => [CAL_JULIAN, cal_days_in_month(CAL_JULIAN, date('m'), date('Y'))],
        ];
    }

    /**
     * @return array
     */
    public function days_in_monthProvider()
    {
        return [
            'null, null' => [null, null, 'TypeError'],
            '\'\', \'\'' => ['', '', 'TypeError'],
            '\' \', \' \'' => [' ', ' ', 'TypeError'],
            '1, 2016' => [0, 1, 2016, 31],
            '1, 2016' => [1, 2016, 31],
            '2, 2015' => [2, 2015, 28],
            '2, 2016' => [2, 2016, 29],
            '4, 2016' => [4, 2016, 30],
            '4, 1900' => [4, 1900, 30],
            '4, 2400' => [4, 2400, 30],
        ];
    }

    /**
     * @return array
     */
    public function days_in_current_monthProvider()
    {
        return [
            '' => [cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'))],
        ];
    }
}
