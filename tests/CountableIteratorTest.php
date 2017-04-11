<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\CountableIterator;

class CountableIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testCountableIterator()
    {
        $input = [1, 2, 3, 4, 5, 6, 7, 8, 9, 20];

        $iterator = new \IteratorIterator(new \ArrayIterator($input));
        $result = new CountableIterator($iterator);
        $this->assertEquals(10, count($input), 'Input array is of wrong size.');
        $this->assertEquals(1, count($iterator), 'IteratorIterator should not be countable.');
        $this->assertEquals(10, count($result), 'CountableIterator did not count correctly.');
    }
}
