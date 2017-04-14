<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\EventIterator;
use Gielfeldt\Iterators\ValuesIterator;

class EventIteratorTest extends IteratorsTestBase
{
    public function testEventIterator()
    {
        $iterator = new EventIterator();
        $iterator->onRewind(function ($iterator) {
            $iterator->setInnerIterator(new \ArrayIterator(range(1, 10)));
        });

        $iterator->onFinished(function ($iterator) {
            $iterator->onFinished(null);
            $iterator->setInnerIterator(new \ArrayIterator(range(11, 20)));
            return true;
        });

        $this->assertEquals(range(1, 20), iterator_to_array(new ValuesIterator($iterator)), 'Inner iterator was not set correctly.');
    }
}
