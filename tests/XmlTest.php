<?php

namespace Padosoft\Support\Test;

class XmlTest extends \PHPUnit_Framework_TestCase
{
    use \Padosoft\Support\Test\XmlDataProvider;

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
     * @param $attrib
     * @param $priority
     * @param $expected
     * @dataProvider xmlProvider
     */
    public function xml2arrayTest($val, $attrib, $priority, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            xml2array($val, $attrib, $priority);
        } else {
            $this->assertEquals($expected, xml2array($val, $attrib, $priority));
        }
    }

    /**
     * @test
     * @param $val
     * @param $attrib
     * @param $priority
     * @param $expected
     * @dataProvider xmlOkProvider
     */
    public function xml2arrayOkTest($val, $attrib, $priority, $expected)
    {
        $arr = xml2array($val, $attrib, $priority);
        $this->assertEquals($expected, is_array($arr));
        $this->assertEquals($expected, count($arr)>0);
        $this->assertEquals($expected, array_key_exists('root', $arr));
        $this->assertEquals($expected, is_array($arr['root']));
        $this->assertEquals($expected, count($arr['root'])>0);
        $this->assertEquals($expected, array_key_exists('child1', $arr['root']));
        $this->assertEquals($expected, is_array($arr['root']['child1']));
        $this->assertEquals($expected, count($arr['root']['child1'])>0);
        $this->assertEquals($expected, array_key_exists('child1child1', $arr['root']['child1']));
        if(is_array($arr['root']['child1']['child1child1'])){
            $this->assertEquals($expected, count($arr['root']['child1']['child1child1'])==0);
        }elseif($arr['root']['child1']['child1child1']!=''){
            $this->assertEquals($expected, $arr['root']['child1']['child1child1']=='ciaone');
        }
    }
    /**
     * @test
     * @param $val
     * @param $rootXml
     * @param $expected
     * @dataProvider array2xmlProvider
     */
    public function array2xmlTest($val, $rootXml, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            array2xml($val, $rootXml);
        } else {
            $this->assertEquals(str_replace(["\r","\n"],['',''],trim($expected)), str_replace(["\r","\n"],['',''],trim(array2xml($val, $rootXml))));
        }
    }
}
