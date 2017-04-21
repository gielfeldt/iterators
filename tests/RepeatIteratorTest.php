<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\RepeatIterator;
use Gielfeldt\Iterators\MapIterator;

class RepeatIteratorTest extends IteratorsTestBase
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

    public function testFractionalRepeat()
    {
        $input1 = new \ArrayIterator(range(1, 40));
        $input2 = new \ArrayIterator(range(1, 40));

        $iterator = new RepeatIterator($input1, 1);
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(40, count($result));
        $this->assertEquals(40, count($iterator));

        $iterator = new RepeatIterator($input1, 0.25);
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(10, count($result));
        $this->assertEquals(10, count($iterator));

        $iterator = new RepeatIterator($input1, 1.75);
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(70, count($result));
        $this->assertEquals(70, count($iterator));

        $iterator = new RepeatIterator($input2, 0.25);
        $this->assertEquals(10, count($iterator));
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(10, count($result));

        $iterator = new RepeatIterator($input2, 1.75);
        $this->assertEquals(70, count($iterator));
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(70, count($result));

        $iterator = new RepeatIterator($input1, INF);
        $this->assertEquals(0, count($iterator));
        $this->assertEquals(INF, $iterator->count());
    }

    public function testFractionalRepeatNonCountable()
    {
        $input1 = new \IteratorIterator(new \ArrayIterator(range(1, 40)));
        $input2 = new \IteratorIterator(new \ArrayIterator(range(1, 40)));

        $iterator = new RepeatIterator($input1, 1);
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(40, count($result));
        $this->assertEquals(40, count($iterator));

        $iterator = new RepeatIterator($input1, 0.25);
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(10, count($result));
        $this->assertEquals(10, count($iterator));

        $iterator = new RepeatIterator($input1, 1.75);
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(70, count($result));
        $this->assertEquals(70, count($iterator));

        $iterator = new RepeatIterator($input2, 0.25);
        $this->assertEquals(10, count($iterator));
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(10, count($result));

        $iterator = new RepeatIterator($input2, 1.75);
        $this->assertEquals(70, count($iterator));
        $result = iterator_to_array($iterator, false);
        $this->assertEquals(70, count($result));

        $iterator = new RepeatIterator($input1, INF);
        $this->assertEquals(0, count($iterator));
        $this->assertEquals(INF, $iterator->count());
    }

    public function mapFunction($iterator)
    {
        $number = str_replace('test', '', $iterator->current());
        return [$iterator->getCurrentIteration() . ':key:' . $number, 'value:' . $iterator->current()];
    }
}
