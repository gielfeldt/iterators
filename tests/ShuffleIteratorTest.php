<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\ShuffleIterator;

class ShuffleIteratorTest extends IteratorsTestBase
{
    public function testShuffleIterator()
    {
        $input = range(1, 20);
        $iterator = new ShuffleIterator(new \ArrayIterator($input));
        $result = iterator_to_array($iterator);
        $this->assertNotEquals(array_keys($input), array_keys($result), 'Inner iterator was not shuffled correctly.');
        $this->assertNotEquals(array_values($input), array_values($result), 'Inner iterator was not shuffled correctly.');

        sort($result);
        $this->assertEquals($input, $result, 'Inner iterator was not shuffled correctly.');

        $this->assertCount(20, $iterator, 'Inner iterator was not shuffled correctly.');
        $this->assertEquals(1, $iterator->min(), 'Inner iterator minimum was not correctly set.');
        $this->assertEquals(20, $iterator->max(), 'Inner iterator maximum was not correctly set.');

        $this->assertGreaterThanOrEqual(1, $iterator->first(), 'Inner iterator first was not correctly set.');
        $this->assertLessThanOrEqual(20, $iterator->first(), 'Inner iterator first was not correctly set.');
        $this->assertGreaterThanOrEqual(1, $iterator->last(), 'Inner iterator last was not correctly set.');
        $this->assertLessThanOrEqual(20, $iterator->last(), 'Inner iterator last was not correctly set.');
    }
}
