<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\InfiniteIterator;
use Gielfeldt\Iterators\KeysIterator;
use Gielfeldt\Iterators\ValuesIterator;
use Gielfeldt\Iterators\MapIterator;

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


        $iterator = new \LimitIterator($iterator, 4, 4);
        $iterator = new MapIterator($iterator, function ($iterator) {
            return [$iterator->getCurrentIteration() . ':' . $iterator->key(), $iterator->current()];
        });

        $expected = ['1:0' => 1, '1:1' => 2, '1:2' => 3, '1:3' => 4];

        $this->assertEquals($expected, iterator_to_array($iterator), 'Limited infinite iterator did not work, keys are wrong.');
    }
}
