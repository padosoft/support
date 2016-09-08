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
        $this->assertEquals('bar', $obj->foo);
        $this->assertEquals('qux', $obj->baz);

        $array = [
            'foo' => 'bar',
            'baz' => [ 'foo2' => 'bar2',
                        'baz2' => 'ok',
            ],
        ];
        $obj = arrayToObject($array);
        $this->assertInternalType('object', $obj);
        $this->assertEquals('bar', $obj->foo);
        $this->assertInternalType('object', $obj->baz);
        $this->assertEquals('bar2', $obj->baz->foo2);
        $this->assertEquals('ok', $obj->baz->baz2);
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

    public function test_isNullOrEmptyArray()
    {
        $this->assertTrue(isNullOrEmptyArray([]));
        $this->assertFalse(isNullOrEmptyArray(['']));
        $this->assertFalse(isNullOrEmptyArray([' ']));
        $this->assertFalse(isNullOrEmptyArray([1,2,3,4]));
        $this->assertFalse(isNullOrEmptyArray(['1' => 1,'2' => 2,'3' => 3,'4' => 4]));
    }

    public function test_isNotNullOrEmptyArray()
    {
        $this->assertFalse(isNotNullOrEmptyArray([]));
        $this->assertTrue(isNotNullOrEmptyArray(['']));
        $this->assertTrue(isNotNullOrEmptyArray([' ']));
        $this->assertTrue(isNotNullOrEmptyArray([1,2,3,4]));
        $this->assertTrue(isNotNullOrEmptyArray(['1' => 1,'2' => 2,'3' => 3,'4' => 4]));
    }

    public function test_isNullOrEmptyArrayKey()
    {
        $this->assertTrue(isNullOrEmptyArrayKey([],'2',true));
        $this->assertTrue(isNullOrEmptyArrayKey([''],'2',true));
        $this->assertTrue(isNullOrEmptyArrayKey([' '],'2',true));
        $this->assertFalse(isNullOrEmptyArrayKey([1,2,3,4],'2',true));
        $this->assertFalse(isNullOrEmptyArrayKey(['1' => 1,'2' => 2,'3' => 3,'4' => 4],'2',true));
        $this->assertTrue(isNullOrEmptyArrayKey(['1' => 1,'2' => '','3' => 3,'4' => 4],'2',false));
        $this->assertTrue(isNullOrEmptyArrayKey(['1' => 1,'2' => '','3' => 3,'4' => 4],'2',true));
        $this->assertFalse(isNullOrEmptyArrayKey(['1' => 1,'2' => ' ','3' => 3,'4' => 4],'2',false));
        $this->assertTrue(isNullOrEmptyArrayKey(['1' => 1,'2' => ' ','3' => 3,'4' => 4],'2',true));
    }

    public function test_isNotNullOrEmptyArrayKey()
    {
        $this->assertFalse(isNotNullOrEmptyArrayKey([],'2',true));
        $this->assertFalse(isNotNullOrEmptyArrayKey([''],'2',true));
        $this->assertFalse(isNotNullOrEmptyArrayKey([' '],'2',true));
        $this->assertTrue(isNotNullOrEmptyArrayKey([1,2,3,4],'2',true));
        $this->assertTrue(isNotNullOrEmptyArrayKey(['1' => 1,'2' => 2,'3' => 3,'4' => 4],'2',true));
        $this->assertFalse(isNotNullOrEmptyArrayKey(['1' => 1,'2' => '','3' => 3,'4' => 4],'2',false));
        $this->assertFalse(isNotNullOrEmptyArrayKey(['1' => 1,'2' => '','3' => 3,'4' => 4],'2',true));
        $this->assertTrue(isNotNullOrEmptyArrayKey(['1' => 1,'2' => ' ','3' => 3,'4' => 4],'2',false));
        $this->assertFalse(isNotNullOrEmptyArrayKey(['1' => 1,'2' => ' ','3' => 3,'4' => 4],'2',true));
    }

    public function test_array_get_key_value_safe()
    {
        $this->assertEquals('default', array_get_key_value_safe([],'2','default'));
        $this->assertEquals('default', array_get_key_value_safe([''],'2','default'));
        $this->assertEquals('default', array_get_key_value_safe([' '],'2','default'));
        $this->assertEquals(3, array_get_key_value_safe([1,2,3,4],'2','default'));
        $this->assertEquals(2, array_get_key_value_safe(['1' => 1,'2' => 2,'3' => 3,'4' => 4],'2',2));
    }
}
