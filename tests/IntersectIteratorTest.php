<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\IntersectIterator;

class IntersectIteratorTest extends IteratorsTestBase
{
    public function testDiffIterator()
    {
        $input1 = ['test1', 'test2', 'test3', 'test4', 'test5', 'test6'];
        $input2 = ['test1', 'test5', 'test6'];
        $input3 = ['test1', 'test3', 'test6'];
        $expected = [0 => 'test1', 5 => 'test6'];

        $iterator = new IntersectIterator(new \ArrayIterator($input1), new \ArrayIterator($input2), new \ArrayIterator($input3));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not diffed correctly.');
    }

    public function testDiffIteratorKey()
    {
        $input1 = array_flip(['test1', 'test2', 'test3', 'test4', 'test5', 'test6']);
        $input2 = array_flip(['test1', 'test5', 'test6']);
        $input3 = array_flip(['test1', 'test3', 'test6']);
        $expected = array_flip([0 => 'test1', 5 => 'test6']);


        $iterator = new IntersectIterator(new \ArrayIterator($input1), new \ArrayIterator($input2), new \ArrayIterator($input3));
        $iterator->setDiff([IntersectIterator::class, 'diffKey']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not diffed correctly.');
    }

    public function testDiffIteratorCustom()
    {
        $input1 = ['test1', 'test2', 'test3', 'test4', 'test5', 'test6'];
        $input2 = ['test5', 5 => 'test6'];
        $input3 = ['test1', 'test3', 5 => 'test6'];
        $expected = [5 => 'test6'];

        $iterator = new IntersectIterator(new \ArrayIterator($input1), new \ArrayIterator($input2), new \ArrayIterator($input3));
        $iterator->setDiff(function ($iterator, $key, $value) {
            return $iterator->key() == $key && $iterator->current() == $value;
        });
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not diffed correctly.');
    }

}
