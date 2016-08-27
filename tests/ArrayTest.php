<?php

namespace Padosoft\Support\Test;

class ArrayTest extends \PHPUnit_Framework_TestCase
{

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
     * Object to array conversion.
     */
    public function test_objectToArray()
    {
        $obj = new \stdClass();
        $obj->foo = 'bar';
        $obj->baz = 'qux';
        $array = objectToArray($obj);
        $this->assertInternalType('array', $array);
    }
    public function test_arrayToObject()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];
        $obj = arrayToObject($array);
        $this->assertInternalType('object', $obj);
    }
    public function test_arrayToString()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];
        $string = arrayToString($array);
        $this->assertEquals(
            'foo="bar" baz="qux"',
            $string
        );
    }
}
