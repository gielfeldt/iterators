<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\DiffIterator;

class DiffIteratorTest extends IteratorsTestBase
{
    public function testDiffIterator()
    {
        $input1 = ['test1', 'test2', 'test3', 'test4', 'test5', 'test6'];
        $input2 = ['test5', 'test6'];
        $input3 = ['test1', 'test3'];
        $expected = [1 => 'test2', 3 => 'test4'];

        $iterator = new DiffIterator(new \ArrayIterator($input1), new \ArrayIterator($input2), new \ArrayIterator($input3));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not diffed correctly.');
    }

    public function testDiffIteratorKey()
    {
        $input1 = array_flip(['test1', 'test2', 'test3', 'test4', 'test5', 'test6']);
        $input2 = array_flip(['test5', 'test6']);
        $input3 = array_flip(['test1', 'test3']);
        $expected = array_flip([1 => 'test2', 3 => 'test4']);

        $iterator = new DiffIterator(new \ArrayIterator($input1), new \ArrayIterator($input2), new \ArrayIterator($input3));
        $iterator->setDiff([DiffIterator::class, 'diffKey']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not diffed correctly.');
    }

    public function testDiffIteratorCustom()
    {
        $input1 = ['test1', 'test2', 'test3', 'test4', 'test5', 'test6'];
        $input2 = ['test5', 5 => 'test6'];
        $input3 = ['test1', 'test3'];
        $expected = [1 => 'test2', 2 => 'test3', 'test4', 'test5'];

        $iterator = new DiffIterator(new \ArrayIterator($input1), new \ArrayIterator($input2), new \ArrayIterator($input3));
        $iterator->setDiff(function ($iterator, $key, $value) {
            return $iterator->key() == $key && $iterator->current() == $value;
        });
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not diffed correctly.');
    }

}
