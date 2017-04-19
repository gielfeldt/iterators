<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\FiniteIterator;
use Gielfeldt\Iterators\KeysIterator;
use Gielfeldt\Iterators\ValuesIterator;

class FiniteIteratorTest extends IteratorsTestBase
{
    public function testFiniteIterator()
    {
        $input = [1, 2, 3, 4];
        $iterator = new FiniteIterator(new \ArrayIterator($input), function ($iterator) {
            return $iterator->current() != 4;
        });

        $iterator = new \LimitIterator($iterator, 0, 10);
        $expectedKeys = [0, 1, 2];
        $expectedValues = [1, 2, 3];

        $this->assertEquals($expectedKeys, iterator_to_array(new KeysIterator($iterator)), 'Limited finite iterator did not work, keys are wrong.');
        $this->assertEquals($expectedValues, iterator_to_array(new ValuesIterator($iterator)), 'Limited finite iterator did not work, values are wrong.');
    }

    public function testFiniteIteratorReindex()
    {
        $input = [1, 2, 3, 4];
        $iterator = new FiniteIterator(new \ArrayIterator($input), function ($iterator) {
            return $iterator->key() != 8;
        }, FiniteIterator::REINDEX);

        $iterator = new \LimitIterator($iterator, 0, 10);
        $expectedKeys = [0, 1, 2, 3, 4, 5, 6, 7];
        $expectedValues = [1, 2, 3, 4, 1, 2, 3, 4];

        $this->assertEquals($expectedKeys, iterator_to_array(new KeysIterator($iterator)), 'Limited finite iterator did not work, keys are wrong.');
        $this->assertEquals($expectedValues, iterator_to_array(new ValuesIterator($iterator)), 'Limited finite iterator did not work, values are wrong.');
    }
}
