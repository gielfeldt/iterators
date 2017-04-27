<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\StepIterator;

class StepIteratorTest extends IteratorsTestBase
{
    public function testStepIterator()
    {
        $input = new \ArrayIterator(range(1, 20));
        $iterator = new StepIterator($input, 1);
        $this->assertEquals(range(1, 20, 1), iterator_to_array($iterator, false), 'Inner iterator was not stepped correctly.');

        $input = new \ArrayIterator(range(1, 20));
        $iterator = new StepIterator($input, 2);
        $this->assertEquals(range(1, 20, 2), iterator_to_array($iterator, false), 'Inner iterator was not stepped correctly.');

        $input = new \ArrayIterator(range(1, 20));
        $iterator = new StepIterator($input, 3);
        $this->assertEquals(range(1, 20, 3), iterator_to_array($iterator, false), 'Inner iterator was not stepped correctly.');
    }
}
