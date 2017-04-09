<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\MapIterator;

class MapIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testMapIterator()
    {
        $input = ['test1', 'test4', 'test2', 'test3'];
        $expected = ['key:1' => 'value:test1', 'key:4' => 'value:test4', 'key:2' => 'value:test2', 'key:3' => 'value:test3'];

        $iterator = new MapIterator(new \ArrayIterator($input), [$this, 'mapFunction']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not mapped correctly.');
    }

    public function mapFunction($iterator)
    {
        $number = str_replace('test', '', $iterator->current());
        return ['key:' . $number, 'value:' . $iterator->current()];
    }
}
