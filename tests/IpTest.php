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
}
