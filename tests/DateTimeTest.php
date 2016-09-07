<?php

namespace Padosoft\Support\Test;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    use \Padosoft\Support\Test\DateTimeDataProvider;

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * @param $expected
     * @return bool
     */
    protected function expectedIsAnException($expected)
    {
        if (is_array($expected)) {
            return false;
        }

        return strpos($expected, 'Exception') !== false
        || strpos($expected, 'PHPUnit_Framework_') !== false
        || strpos($expected, 'TypeError') !== false;
    }

    /**
     * @test
     * @param $calendar
     * @param $month
     * @param $year
     * @param $expected
     * @dataProvider cal_days_in_monthProvider
     */
    public function cal_days_in_monthTest($calendar, $month, $year, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            cal_days_in_month($calendar, $month, $year);
        } else {
            $this->assertEquals($expected, cal_days_in_month($calendar, $month, $year));
        }
    }

    /**
     * @test
     * @param $calendar
     * @param $expected
     * @dataProvider cal_days_in_current_monthProvider
     */
    public function cal_days_in_current_monthTest($calendar, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            cal_days_in_current_month($calendar);
        } else {
            $this->assertEquals($expected, cal_days_in_current_month($calendar));
        }
    }

    /**
     * @test
     * @param $month
     * @param $year
     * @param $expected
     * @dataProvider days_in_monthProvider
     */
    public function days_in_monthTest($month, $year, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            days_in_month($month, $year);
        } else {
            $this->assertEquals($expected, days_in_month($month, $year));
        }
    }

    /**
     * @test
     * @param $expected
     * @dataProvider days_in_current_monthProvider
     */
    public function days_in_current_monthTest($expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            days_in_current_month();
        } else {
            $this->assertEquals($expected, days_in_current_month());
        }
    }
}
