<?php

namespace Padosoft\Support\Test;

use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    use \Padosoft\Support\Test\ValidationDataProvider;

    protected function setUp(): void
    {
    }

    protected function tearDown(): void
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
     * @dataProvider isDateTimeOrDateTimeZeroIsoProvider
     */
    public function isDateTimeOrDateTimeZeroIsoTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateTimeOrDateTimeZeroIso($val);
        } else {
            $this->assertEquals($expected, isDateTimeOrDateTimeZeroIso($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isDateOrDateZeroIsoProvider
     */
    public function isDateOrDateZeroIsoTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateOrDateZeroIso($val);
        } else {
            $this->assertEquals($expected, isDateOrDateZeroIso($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isDateTimeOrDateTimeZeroItaProvider
     */
    public function isDateTimeOrDateTimeZeroItaTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateTimeOrDateTimeZeroIta($val);
        } else {
            $this->assertEquals($expected, isDateTimeOrDateTimeZeroIta($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isDateOrDateZeroItaProvider
     */
    public function isDateOrDateZeroItaTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDateOrDateZeroIta($val);
        } else {
            $this->assertEquals($expected, isDateOrDateZeroIta($val));
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
     * @dataProvider isIntegerPositiveOrZeroNoFloatingValidateProvider
     */
    public function isIntegerPositiveOrZeroNoFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerPositiveOrZero($val, false);
        } else {
            $this->assertEquals($expected, isIntegerPositiveOrZero($val, false));
        }
    }


    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isIntegerPositiveNoFloatingValidateProvider
     */
    public function isIntegerPositiveNoFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerPositive($val, false);
        } else {
            $this->assertEquals($expected, isIntegerPositive($val, false));
        }
    }

   /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isIntegerNegativeOrZeroNoFloatingValidateProvider
     */
    public function isIntegerNegativeOrZeroNoFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerNegativeOrZero($val, false);
        } else {
            $this->assertEquals($expected, isIntegerNegativeOrZero($val, false));
        }
    }


    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isIntegerNegativeNoFloatingValidateProvider
     */
    public function isIntegerNegativeNoFloatingTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerNegative($val, false);
        } else {
            $this->assertEquals($expected, isIntegerNegative($val, false));
        }
    }

    /**
     * @test
     * @param $val
     * @param $signed
     * @param $expected
     * @dataProvider isIntegerZeroNoFloatingValidateProvider
     */
    public function isIntegerZeroNoFloatingTest($val, $signed, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerZero($val, false, $signed);
        } else {
            $this->assertEquals($expected, isIntegerZero($val, false, $signed));
        }
    }

    /**
     * @test
     * @param $val
     * @param $signed
     * @param $expected
     * @dataProvider isIntegerZeroFloatingValidateProvider
     */
    public function isIntegerZeroFloatingTest($val, $signed, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntegerZero($val, false, $signed);
        } else {
            $this->assertEquals($expected, isIntegerZero($val, false, $signed));
        }
    }

    /**
     * @test
     * @param $val
     * @param $signed
     * @param $expected
     * @dataProvider isNumericValidateProvider
     */
    public function isNumericTest($val, $signed, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isNumeric($val, $signed);
        } else {
            $this->assertEquals($expected, isNumeric($val, $signed));
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
     * @param $value
     * @param $withDecimal
     * @param $withPercentChar
     * @param $expected
     * @dataProvider isPercentProvider
     */
    public function isPercentTest($value, $withDecimal, $withPercentChar, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isPercent($value, $withDecimal, $withPercentChar);
        } else {
            $this->assertEquals($expected, isPercent($value, $withDecimal, $withPercentChar));
        }
    }

    /**
     * @test
     * @param $value
     * @param $expected
     * @dataProvider isIntBoolProvider
     */
    public function isIntBoolTest($value, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIntBool($value);
        } else {
            $this->assertEquals($expected, isIntBool($value));
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

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isHostnameProvider
     */
    public function isHostnameTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isHostname($val);
        } else {
            $this->assertEquals($expected, isHostname($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $checkMX
     * @param $expected
     * @dataProvider isMailProvider
     */
    public function isMailTest($val, $checkMX, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isMail($val, $checkMX);
        } else {
            $this->assertEquals($expected, isMail($val, $checkMX));
        }
    }

    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isCfProvider
     */
    public function isCfTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isCf($val);
        } else {
            $this->assertEquals($expected, isCf($val));
        }
    }

    /**
     * @test
     * @param $val
     * @param $validateOnVies
     * @param $expected
     * @dataProvider isEuVatNumberProvider
     */
    public function isEuVatNumberTest($val, $validateOnVies, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isEuVatNumber($val, $validateOnVies);
        } else {
            $this->assertEquals($expected, isEuVatNumber($val, $validateOnVies));
        }
    }


    /**
     * @test
     * @param $val
     * @param $expected
     * @dataProvider isITVatProvider
     */
    public function isITVatTest($val, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isITVat($val);
        } else {
            $result = isITVat($val);
            $this->assertEquals($expected, $result);
        }
    }

    /**
     * @test
     * @param $val
     * @param $fallback
     * @param $expected
     * @dataProvider getCoutryCodeByVatNumberProvider
     */
    public function getCoutryCodeByVatNumberTest($val, $fallback, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            getCoutryCodeByVatNumber($val, $fallback);
        } else {
            $result = getCoutryCodeByVatNumber($val, $fallback);
            $this->assertEquals($expected, $result);
        }
    }

    /**
     * @test
     * @param $val
     * @param $countryCode
     * @param $expected
     * @dataProvider isVATRegisteredInViesProvider
     * @throws
     */
    public function isVATRegisteredInViesTest($val, $countryCode, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isVATRegisteredInVies($val, $countryCode);
        } else {
            try
            {
                $result = isVATRegisteredInVies($val, $countryCode);
                $this->assertEquals($expected, $result);
            } catch (\SoapFault $e) {
                //$this->expectException('SoapFault');
                $this->assertEquals(true, true);
            }

        }
    }

    /**
     * @test
     * @param $value
     * @param $leftRange
     * @param $rightRange
     * @param $expected
     * @dataProvider isInRangeProvider
     */
    public function isInRangeTest($value, $leftRange, $rightRange, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isInRange($value, $leftRange, $rightRange);
        } else {
                $this->assertEquals($expected, isInRange($value, $leftRange, $rightRange));
        }
    }

    /**
     * @test
     * @param $value
     * @param $expected
     * @dataProvider isDayProvider
     */
    public function isDayTest($value, $month, $year, $calendar, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isDay($value, $month, $year, $calendar);
        } else {
                $this->assertEquals($expected, isDay($value, $month, $year, $calendar));
        }
    }

    /**
     * @test
     * @param $value
     * @param $year
     * @param $calendar
     * @param $expected
     * @dataProvider isMonthProvider
     */
    public function isMonthTest($value, $year, $calendar, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isMonth($value, $year, $calendar);
        } else {
                $this->assertEquals($expected, isMonth($value, $year, $calendar));
        }
    }

    /**
     * @test
     */
    public function isJewishLeapYearTest()
    {
        $this->assertFalse(isJewishLeapYear(2000));
        $this->assertTrue(isJewishLeapYear(2001));
        $this->assertFalse(isJewishLeapYear(2002));
        $this->assertTrue(isJewishLeapYear(2003));
        $this->assertFalse(isJewishLeapYear(2004));
        $this->assertFalse(isJewishLeapYear(2005));
        $this->assertTrue(isJewishLeapYear(2006));
        $this->assertFalse(isJewishLeapYear(2007));
        $this->assertFalse(isJewishLeapYear(2008));
        $this->assertTrue(isJewishLeapYear(2009));
        $this->assertFalse(isJewishLeapYear(2010));
        $this->assertFalse(isJewishLeapYear(2011));
    }

    /**
     * @test
     */
    public function betweenDateIsoTest()
    {
        $this->assertFalse(betweenDateIso('', '', '', false));
        $this->assertFalse(betweenDateIso('33515313', '', '', false));
        $this->assertFalse(betweenDateIso('2016-01-01', 'dfgdfgfd', '', false));
        $this->assertFalse(betweenDateIso('2016-01-01', '2016-01-01', 'dfgdfgfd', false));
        $this->assertTrue(betweenDateIso('2016-01-01', '2016-01-01', '2016-01-01', false));
        $this->assertFalse(betweenDateIso('2016-01-01', '2016-01-01', '2016-01-01', true));
        $this->assertFalse(betweenDateIso('2016-01-10', '2016-01-09', '2016-01-10', true));
        $this->assertFalse(betweenDateIso('2016-01-10', '2016-01-10', '2016-01-11', true));
        $this->assertTrue(betweenDateIso('2016-01-10', '2016-01-09', '2016-01-11', true));
        $this->assertTrue(betweenDateIso('2016-01-10', '2016-01-09', '2016-01-10', false));
        $this->assertTrue(betweenDateIso('2016-01-10', '2016-01-10', '2016-01-11', false));
        $this->assertTrue(betweenDateIso('2016-01-10', '2016-01-09', '2016-01-11', false));
        $this->assertFalse(betweenDateIso('2016-01-10', '2016-02-09', '2016-02-11', false));
        $this->assertFalse(betweenDateIso('2017-01-10', '2016-02-09', '2016-02-11', false));
        $this->assertFalse(betweenDateIso('2016-01-10', '2016-02-09', '2016-02-11', true));
        $this->assertFalse(betweenDateIso('2017-01-10', '2016-02-09', '2016-02-11', true));

        $this->expectException('TypeError');
        $this->assertFalse(betweenDateIso(null, '', '', false));
    }

    /**
     * @test
     */
    public function betweenDateItaTest()
    {
        $this->assertFalse(betweenDateIta('', '', '', false));
        $this->assertFalse(betweenDateIta('33515313', '', '', false));
        $this->assertFalse(betweenDateIta('01/01/2016', 'dfgdfgfd', '', false));
        $this->assertFalse(betweenDateIta('01/01/2016', '01/01/2016', 'dfgdfgfd', false));
        $this->assertTrue(betweenDateIta('01/01/2016', '01/01/2016', '01/01/2016', false));
        $this->assertFalse(betweenDateIta('01/01/2016', '01/01/2016', '01/01/2016', true));
        $this->assertFalse(betweenDateIta('10/01/2016', '09/01/2016', '10/01/2016', true));
        $this->assertFalse(betweenDateIta('10/01/2016', '10/01/2016', '11/01/2016', true));
        $this->assertTrue(betweenDateIta('10/01/2016', '09/01/2016', '11/01/2016', true));
        $this->assertTrue(betweenDateIta('10/01/2016', '09/01/2016', '10/01/2016', false));
        $this->assertTrue(betweenDateIta('10/01/2016', '10/01/2016', '11/01/2016', false));
        $this->assertTrue(betweenDateIta('10/01/2016', '09/01/2016', '11/01/2016', false));
        $this->assertFalse(betweenDateIta('10/01/2016', '09/02/2016', '11/02/2016', false));
        $this->assertFalse(betweenDateIta('10/01/2017', '09/02/2016', '11/02/2016', false));
        $this->assertFalse(betweenDateIta('10/01/2016', '09/02/2016', '11/02/2016', true));
        $this->assertFalse(betweenDateIta('10/01/2017', '09/02/2016', '11/02/2016', true));

        $this->expectException('TypeError');
        $this->assertFalse(betweenDateIta(null, '', '', false));
    }

    /**
     * @test
     */
    public function dateIsoToItaTest()
    {
        $this->assertEquals('00/00/0000', dateIsoToIta(''));
        $this->assertEquals('00/00/0000', dateIsoToIta('545646536'));
        $this->assertEquals('00/00/0000', dateIsoToIta('16/06/2016'));
        $this->assertEquals('00/00/0000', dateIsoToIta('2016-01-1'));
        $this->assertEquals('00/00/0000', dateIsoToIta('2016-1-01'));
        $this->assertEquals('00/00/0000', dateIsoToIta('16-01-01'));
        $this->assertEquals('01/01/2016', dateIsoToIta('2016-01-01'));

        $this->expectException('TypeError');
        $this->assertFalse(dateIsoToIta(null));
    }

    /**
     * @test
     */
    public function dateItaToIsoTest()
    {
        $this->assertEquals('0000-00-00', dateItaToIso(''));
        $this->assertEquals('0000-00-00', dateItaToIso('545646536'));
        $this->assertEquals('0000-00-00', dateItaToIso('2016-01-01'));
        $this->assertEquals('0000-00-00', dateItaToIso('1/01/2016'));
        $this->assertEquals('0000-00-00', dateItaToIso('01/1/2016'));
        $this->assertEquals('0000-00-00', dateItaToIso('01/01/16'));
        $this->assertEquals('2016-01-01', dateItaToIso('01/01/2016'));

        $this->expectException('TypeError');
        $this->assertFalse(dateItaToIso(null));
    }

    /**
     * @test
     * @param $ip
     * @param $expected
     * @dataProvider isIPv4CompatibilityProvider
     */
    public function isIPv4CompatibilityTest($ip, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            isIPv4Compatibility($ip);
        } else {
            $this->assertEquals($expected, isIPv4Compatibility($ip));
        }
    }
}
