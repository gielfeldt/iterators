<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\RepeatIterator;
use Gielfeldt\Iterators\MapIterator;

class RepeatIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testRepeatIterator()
    {
        $input = ['test1', 'test4', 'test2', 'test3'];
        $expected = ['0:key:1' => 'value:test1', '0:key:4' => 'value:test4', '0:key:2' => 'value:test2', '0:key:3' => 'value:test3'];
        $expected += ['1:key:1' => 'value:test1', '1:key:4' => 'value:test4', '1:key:2' => 'value:test2', '1:key:3' => 'value:test3'];
        $expected += ['2:key:1' => 'value:test1', '2:key:4' => 'value:test4', '2:key:2' => 'value:test2', '2:key:3' => 'value:test3'];

        $iterator = new MapIterator(new RepeatIterator(new \ArrayIterator($input), 3), [$this, 'mapFunction']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not mapped correctly.');
    }

    public function testInvalidArgument()
    {
        $input = ['test1', 'test4', 'test2', 'test3'];
        $this->expectException(\InvalidArgumentException::class);
        $iterator = new RepeatIterator(new \ArrayIterator($input), -1);
    }

    public function testZeroIteration()
    {
        $input = ['test1', 'test4', 'test2', 'test3'];
        $iterator = new RepeatIterator(new \ArrayIterator($input), 0);
        $this->assertEquals([], iterator_to_array($iterator));
    }

    public function testGetIterationCount()
    {
        $input = ['test1', 'test4', 'test2', 'test3'];
        $iterator = new RepeatIterator(new \ArrayIterator($input), 5);
        $this->assertEquals(5, $iterator->getIterationCount());
    }

    public function testEmptyIterator()
    {
        $iterator = new RepeatIterator(new \ArrayIterator([]), 10);
        $this->assertEquals([], iterator_to_array($iterator));
    }

    public function mapFunction($iterator)
    {
        $number = str_replace('test', '', $iterator->current());
        return [$iterator->getCurrentIteration() . ':key:' . $number, 'value:' . $iterator->current()];
    }
}
