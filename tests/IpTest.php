<?php

namespace Padosoft\Support\Test;

class IpTest extends \PHPUnit_Framework_TestCase
{
    use \Padosoft\Support\Test\IpDataProvider;

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
     * @param $server
     * @param $trustedProxies
     * @param $expected
     * @dataProvider ipProvider
     */
    public function getClientIpTest($server, $trustedProxies, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            getClientIp($server, $trustedProxies);
        } else {
            $this->assertEquals($expected, getClientIp($server, $trustedProxies));
        }
    }

    /**
     * @test
     * @param $ip
     * @param $expected
     * @dataProvider anonimizeIpProvider
     */
    public function anonimizeIp($ip, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            anonimizeIp($ip);
        } else {
            $this->assertEquals($expected, anonimizeIp($ip));
        }
    }

    /**
     * @test
     * @param $ip
     * @param $expected
     * @dataProvider expandIPv6NotationProvider
     */
    public function expandIPv6NotationTest($ip, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            expandIPv6Notation($ip);
        } else {
            $this->assertEquals($expected, expandIPv6Notation($ip));
        }
    }

    /**
     * @test
     * @param $ip
     * @param $range
     * @param $expected
     * @dataProvider ipInRangeProvider
     */
    public function ipInRangeTest($ip, $range, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            ipInRange($ip, $range);
        } else {
            $this->assertEquals($expected, ipInRange($ip, $range));
        }
    }
}
