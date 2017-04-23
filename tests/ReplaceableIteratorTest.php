<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\MapIterator;
use Gielfeldt\Iterators\ReplaceableIterator;

class ReplaceableIteratorTest extends IteratorsTestBase
{
    public function testReplaceableIterator()
    {
        $iterator = new ReplaceableIterator();

        $iterator->setInnerIterator(new \ArrayIterator(range(1, 10)));
        $this->assertEquals(range(1, 10), iterator_to_array($iterator), 'Inner iterator was not set correctly.');
        $this->assertEquals(range(1, 10), $iterator->getArrayCopy(), 'Call to inner iterator not dispatched.');

        $iterator->setInnerIterator(new \ArrayIterator(range(11, 20)));
        $this->assertEquals(range(11, 20), iterator_to_array($iterator), 'Inner iterator was not set correctly.');
        $this->assertEquals(range(11, 20), $iterator->getArrayCopy(), 'Method not delegated correctly.');
    }

    public function testReplaceableIteratorIndex()
    {
        $input = array_combine(range(10, 1, -1), range(1, 10));
        $iterator = new ReplaceableIterator(new \ArrayIterator($input));

        $this->assertEquals($input, iterator_to_array($iterator), 'Inner iterator was not set correctly.');
        $this->assertEquals($input, $iterator->getArrayCopy(), 'Call to inner iterator not dispatched.');

        $indexes = new MapIterator($iterator, function ($iterator) {
            return $iterator->getIndex();
        });

        $this->assertEquals(range(0, 9), iterator_to_array($indexes), 'Iterator indexes were not correct.');
    }
}
