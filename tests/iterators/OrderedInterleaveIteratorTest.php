<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\OrderedInterleaveIterator;

class OrderedInterleaveIteratorTest extends IteratorsTestBase
{
    public function testInterleaveIterator()
    {
        $input1 = [1, 4, 7, 10, 13];
        $input2 = [3, 6, 9, 12, 14];
        $input3 = [2, 5, 8, 11];
        $expected1 = [3, 6, 9, 12, 14];
        $expected2 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];

        $iterator = new OrderedInterleaveIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            new \ArrayIterator($input3)
        );

        $this->assertEquals($expected1, iterator_to_array($iterator), 'Iterator was not interleave correctly.');
        $this->assertEquals($expected2, iterator_to_array($iterator, false), 'Iterator was not interleave correctly.');
    }
}
