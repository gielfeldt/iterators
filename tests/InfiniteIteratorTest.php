<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\InfiniteIterator;
use Gielfeldt\Iterators\KeysIterator;
use Gielfeldt\Iterators\ValuesIterator;

class InfiniteIteratorTest extends IteratorsTestBase
{
    public function testInfiniteIterator()
    {
        $input = [1, 2, 3, 4];
        $iterator = new InfiniteIterator(new \ArrayIterator($input));

        $iterator = new \LimitIterator($iterator, 0, 10);
        $expectedKeys = [0, 1, 2, 3, 0, 1, 2, 3, 0, 1];
        $expectedValues = [1, 2, 3, 4, 1, 2, 3, 4, 1, 2];

        $this->assertEquals($expectedKeys, iterator_to_array(new KeysIterator($iterator)), 'Limited infinite iterator did not work, keys are wrong.');
        $this->assertEquals($expectedValues, iterator_to_array(new ValuesIterator($iterator)), 'Limited infinite iterator did not work, values are wrong.');
    }

    public function testInfiniteIteratorWithEndCondition()
    {
        $input = [1, 2, 3, 4];
        $iterator = new InfiniteIterator(new \ArrayIterator($input), function ($iterator) {
            return $iterator->current() != 4;
        });

        $iterator = new \LimitIterator($iterator, 0, 10);
        $expectedKeys = [0, 1, 2];
        $expectedValues = [1, 2, 3];

        $this->assertEquals($expectedKeys, iterator_to_array(new KeysIterator($iterator)), 'Limited infinite iterator did not work, keys are wrong.');
        $this->assertEquals($expectedValues, iterator_to_array(new ValuesIterator($iterator)), 'Limited infinite iterator did not work, values are wrong.');

        $iterator = new InfiniteIterator(new \ArrayIterator($input), function ($iterator) {
            return $iterator->getCurrentIteration() < 1 || $iterator->current() != 4;
        });

        $iterator = new \LimitIterator($iterator, 0, 10);
        $expectedKeys = [0, 1, 2, 3, 0, 1, 2];
        $expectedValues = [1, 2, 3, 4, 1, 2, 3];

        $this->assertEquals($expectedKeys, iterator_to_array(new KeysIterator($iterator)), 'Limited infinite iterator did not work, keys are wrong.');
        $this->assertEquals($expectedValues, iterator_to_array(new ValuesIterator($iterator)), 'Limited infinite iterator did not work, values are wrong.');
    }
}
