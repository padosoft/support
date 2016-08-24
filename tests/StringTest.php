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
}
