<?php

namespace Padosoft\Support\Test;

class SanitizeTest extends \PHPUnit_Framework_TestCase
{
    use \Padosoft\Support\Test\SanitizeDataProvider;

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
     * @dataProvider normalizeUtf8StringProvider
     */
    public function normalizeUtf8StringTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            normalizeUtf8String($val);
        } else {
            $this->assertEquals($expected, normalizeUtf8String($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider sanitize_filenameProvider
     */
    public function sanitize_filenameTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            sanitize_filename($val);
        } else {
            $this->assertEquals($expected, sanitize_filename($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider sanitize_filenameNoWiteSpacesProvider
     */
    public function sanitize_filenameNoWiteSpacesTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            sanitize_filename($val, false, '_');
        } else {
            $this->assertEquals($expected, sanitize_filename($val, false, '_'));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider sanitize_pathnameProvider
     */
    public function sanitize_pathnameSpacesTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            sanitize_pathname($val, '_');
        } else {
            $this->assertEquals($expected, sanitize_pathname($val, '_'));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider sheProvider
     */
    public function sheTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            she($val);
        } else {
            $this->assertEquals($expected, she($val));
        }
    }
}
