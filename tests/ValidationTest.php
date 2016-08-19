<?php

namespace Padosoft\Support\Test;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    use \Padosoft\Support\Test\ValidationDataProvider;

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
     * @dataProvider dateTimeIsoValidateProvider
     */
    public function isDateTimeIsoTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateTimeIso($val);
        } else {
            $this->assertEquals($expected, isDateTimeIso($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider dateTimeItaValidateProvider
     */
    public function isDateTimeItaTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateTimeIta($val);
        } else {
            $this->assertEquals($expected, isDateTimeIta($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider dateIsoValidateProvider
     */
    public function isDateIsoTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateIso($val);
        } else {
            $this->assertEquals($expected, isDateIso($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider dateItaValidateProvider
     */
    public function isDateItaTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateIta($val);
        } else {
            $this->assertEquals($expected, isDateIta($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider timeIsoValidateProvider
     */
    public function isTimeIsoTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isTimeIso($val);
        } else {
            $this->assertEquals($expected, isTimeIso($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider timeIsoValidateProvider
     */
    public function isTimeItaTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isTimeIta($val);
        } else {
            $this->assertEquals($expected, isTimeIta($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isIntegerUnsignedNoFloatingValidateProvider
     */
    public function isIntegerUnsignedNoFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isInteger($val, true, false);
        } else {
            $this->assertEquals($expected, isInteger($val, true, false));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isIntegerSignedNoFloatingValidateProvider
     */
    public function isIntegerSignedNoFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isInteger($val, false, false);
        } else {
            $this->assertEquals($expected, isInteger($val, false, false));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isIntegerUnsignedFloatingValidateProvider
     */
    public function isIntegerUnsignedFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerFloatingPoint($val, true);
        } else {
            $this->assertEquals($expected, isIntegerFloatingPoint($val, true));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isIntegerSignedFloatingValidateProvider
     */
    public function isIntegerSignedFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerFloatingPoint($val, false);
        } else {
            $this->assertEquals($expected, isIntegerFloatingPoint($val, false));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isFloatingPointUnsignedValidateProvider
     */
    public function isFloatingPointUnsignedTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isFloatingPoint($val, true);
        } else {
            $this->assertEquals($expected, isFloatingPoint($val, true));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isFloatingPointSignedValidateProvider
     */
    public function isFloatingPointSignedTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isFloatingPoint($val, false);
        } else {
            $this->assertEquals($expected, isFloatingPoint($val, false));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isDoubleUnSigned2DecProvider
     */
    public function isDoubleUnSigned2DecTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDouble($val, 2, true);
        } else {
            $this->assertEquals($expected, isDouble($val, 2, true));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isDoubleSigned2DecProvider
     */
    public function isDoubleSigned2DecTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDouble($val, 2, false);
        } else {
            $this->assertEquals($expected, isDouble($val, 2, false));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isDoubleUnSigned2DecExactProvider
     */
    public function isDoubleUnSigned2DecExactTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDouble($val, 2, true, true);
        } else {
            $this->assertEquals($expected, isDouble($val, 2, true, true));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isDoubleSigned2DecExactProvider
     */
    public function isDoubleSigned2DecExactTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDouble($val, 2, false, true);
        } else {
            $this->assertEquals($expected, isDouble($val, 2, false, true));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isUrlProvider
     */
    public function isUrlTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isUrl($val);
        } else {
            $this->assertEquals($expected, isUrl($val));
        }
    }
}
