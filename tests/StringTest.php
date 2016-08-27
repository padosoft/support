<?php

namespace Padosoft\Support\Test;

class StringTest extends \PHPUnit_Framework_TestCase
{
    use \Padosoft\Support\Test\StringDataProvider;

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
     * @param $val
     * @param $expected
     * @dataProvider str_replace_multiple_spaceProvider
     */
    public function str_replace_multiple_spaceTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            str_replace_multiple_space($val);
        } else {
            $this->assertEquals($expected, str_replace_multiple_space($val));
        }
    }

    /**
     * @test
     * @param $search
     * @param $replace
     * @param $subject
     * @param $expected
     * @dataProvider str_replace_lastProvider
     */
    public function str_replace_lastTest($search, $replace, $subject, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            str_replace_last($search, $replace, $subject);
        } else {
            $this->assertEquals($expected, str_replace_last($search, $replace, $subject));
        }
    }

    /**
     * @test
     * @param $len
     * @param $secLevel
     * @param $expected
     * @dataProvider generateRandomPasswordProvider
     */
    public function generateRandomPasswordTest($len, $secLevel, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            generateRandomPassword($len, $secLevel);
        } else {
            $psw = generateRandomPassword($len, $secLevel);
            if ($len < 1) {
                $len = 1;
            }
            $this->assertEquals($len, strlen($psw));
            if ($secLevel < 1) {
                $this->assertRegExp('/[a-z]{1,' . $len . '}/', $psw);
            } elseif ($secLevel == 1) {
                $this->assertRegExp('/[a-z0-9]{1,' . $len . '}/', $psw);
            } elseif ($secLevel == 2) {
                $this->assertRegExp('/[A-Za-z0-9]{1,' . $len . '}/', $psw);
            } elseif ($secLevel >= 3) {
                $this->assertRegExp('/[A-Za-z0-9-_$!+&%?=*#@]{1,' . $len . '}/', $psw);
            }
        }
    }


    /**
     * Convert number to word.
     */
    public function test_numberToWord()
    {
        $number = '864210';
        $word = numberToWord($number, 'EN');
        $this->assertEquals(
            'eight hundred and sixty-four thousand, two hundred and ten',
            $word
        );
        $word = numberToWord($number);
        $this->assertEquals(
            'otto cento sessantaquattro mila, due cento dieci',
            $word
        );
    }
    /**
     * Convert number of seconds to time.
     */
    public function test_secondsToText()
    {
        $seconds = 60*60*24*2;
        $duration = secondsToText($seconds, false, 'EN');
        $this->assertEquals(
            '2 days',
            $duration
        );
        $duration = secondsToText($seconds, false);
        $this->assertEquals(
            '2 giorni',
            $duration
        );

        $seconds = 3610;
        $duration = secondsToText($seconds, false, 'EN');
        $this->assertEquals(
            '1 hour and 10 seconds',
            $duration
        );
        $duration = secondsToText($seconds, $returnAsWords = true, 'EN');
        $this->assertEquals(
            'one hour and ten seconds',
            $duration
        );

        $duration = secondsToText($seconds, false);
        $this->assertEquals(
            '1 ora e 10 secondi',
            $duration
        );
        $duration = secondsToText($seconds, $returnAsWords = true);
        $this->assertEquals(
            'uno ora e dieci secondi',
            $duration
        );
    }
    /**
     * Convert number of minutes to time.
     */
    public function test_minutesToText()
    {
        $minutes = 60 * 24 * 2;
        $duration = minutesToText($minutes, false, 'EN');
        $this->assertEquals(
            '2 days',
            $duration
        );
        $duration = minutesToText($minutes, $returnAsWords = true, 'EN');
        $this->assertEquals(
            'two days',
            $duration
        );
        $duration = minutesToText($minutes);
        $this->assertEquals(
            '2 giorni',
            $duration
        );
        $duration = minutesToText($minutes, $returnAsWords = true);
        $this->assertEquals(
            'due giorni',
            $duration
        );
    }
    /**
     * Convert hours to text.
     */
    public function test_hoursToText()
    {
        $hours = 4.2;
        $duration = hoursToText($hours, false, 'EN');
        $this->assertEquals(
            '4 hours and 12 minutes',
            $duration
        );
        $duration = hoursToText($hours, $returnAsWords = true, 'EN');
        $this->assertEquals(
            'four hours and twelve minutes',
            $duration
        );

        $duration = hoursToText($hours);
        $this->assertEquals(
            '4 ore e 12 minuti',
            $duration
        );
        $duration = hoursToText($hours, $returnAsWords = true);
        $this->assertEquals(
            'quattro ore e dodici minuti',
            $duration
        );
    }

    /**
     * Shorten the string.
     */
    public function test_shortenString()
    {
        $string = 'The quick brown fox jumps over the lazy dog';
        $shortenString = str_limit($string, 20);
        $this->assertEquals(
            'The quick brown fox...',
            $shortenString
        );
        $shortenString = str_limit($string, 20, '');
        $this->assertEquals(
            'The quick brown fox',
            $shortenString
        );
        $shortenString = str_limit($string, 23, '', true);
        $this->assertEquals(
            'The quick brown fox',
            $shortenString
        );
    }

}
