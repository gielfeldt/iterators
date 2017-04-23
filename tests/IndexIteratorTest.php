<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\IndexIterator;

class IndexIteratorTest extends IteratorsTestBase
{
    public function testIndexIterator()
    {
        $input = range(1, 20);
        $indexes = [1, 5, 7, 14];
        $iterator = new IndexIterator(new \ArrayIterator($input), new \ArrayIterator($indexes));
        $expected = [1 => 2, 5 => 6, 7 => 8, 14 => 15];

        $this->assertEquals($expected, iterator_to_array($iterator), 'Indexed infinite iterator did not work.');
    }
}
