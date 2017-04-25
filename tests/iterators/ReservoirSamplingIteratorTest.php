<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\ReservoirSamplingIterator;

class ReservoirSamplingIteratorTest extends IteratorsTestBase
{
    public function testReservoirSamplingIterator()
    {
        $input = range(1, 20);
        $iterator = new ReservoirSamplingIterator(new \ArrayIterator($input), 5);

        $this->assertCount(5, $iterator, 'ReservoirSampling iterator did not work.');
        $this->assertCount(5, array_unique(iterator_to_array($iterator)), 'ReservoirSampling iterator did not work.');
        foreach ($iterator as $v) {
            $this->assertGreaterThanOrEqual(1, $v, 'ReservoirSampling iterator contains incorrect values.');
            $this->assertLessThanOrEqual(20, $v, 'ReservoirSampling iterator contains incorrect values.');
        }

    }

    public function testReservoirSamplingIteratorTooSmall()
    {
        $input = range(1, 20);
        $iterator = new ReservoirSamplingIterator(new \ArrayIterator($input), 50);

        $this->assertCount(20, $iterator, 'ReservoirSamplinged infinite iterator did not work.');
        $this->assertCount(20, array_unique(iterator_to_array($iterator)), 'ReservoirSampling iterator did not work.');
        foreach ($iterator as $v) {
            $this->assertGreaterThanOrEqual(1, $v, 'ReservoirSampling iterator contains incorrect values.');
            $this->assertLessThanOrEqual(20, $v, 'ReservoirSampling iterator contains incorrect values.');
        }
    }
}
