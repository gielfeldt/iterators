<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\RandomIterator;

class RandomIteratorTest extends IteratorsTestBase
{
    public function testRandomIterator()
    {
        $input = range(1, 20);
        $iterator = new RandomIterator(new \ArrayIterator($input), 5);

        $this->assertCount(5, $iterator, 'Random iterator did not work.');
        $this->assertCount(5, array_unique(iterator_to_array($iterator)), 'Random iterator did not work.');
        foreach ($iterator as $v) {
            $this->assertGreaterThanOrEqual(1, $v, 'Random iterator contains incorrect values.');
            $this->assertLessThanOrEqual(20, $v, 'Random iterator contains incorrect values.');
        }
    }

    public function testRandomIteratorTooSmall()
    {
        $input = range(1, 20);
        $iterator = new RandomIterator(new \ArrayIterator($input), 50);

        $this->assertCount(20, $iterator, 'Randomed infinite iterator did not work.');
        $this->assertCount(20, array_unique(iterator_to_array($iterator)), 'Random iterator did not work.');
        foreach ($iterator as $v) {
            $this->assertGreaterThanOrEqual(1, $v, 'Random iterator contains incorrect values.');
            $this->assertLessThanOrEqual(20, $v, 'Random iterator contains incorrect values.');
        }
    }
}
