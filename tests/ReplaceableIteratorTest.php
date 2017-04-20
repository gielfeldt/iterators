<?php

namespace Gielfeldt\Tests\Iterators;

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
        $this->assertEquals(range(11, 20), $iterator->getArrayCopy(), 'Call to inner iterator not dispatched.');
    }
}
