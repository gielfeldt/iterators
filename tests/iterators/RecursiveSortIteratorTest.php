<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\RecursiveSortIterator;

class RecursiveSortIteratorTest extends IteratorsTestBase
{
    public function testSortIterator()
    {
        $input = [
            0 => 'test3',
            1 => 'test1',
            2 => 'test2',
            3 => [
                4 => 'test9',
                5 => 'test5',
                6 => 'test7',
            ],
        ];
        $expected = [
            1 => 'test1',
            0 => 'test3',
            2 => 'test2',
            5 => 'test5',
            6 => 'test7',
            4 => 'test9',
        ];

        $iterator = new \RecursiveArrayIterator($input, \RecursiveArrayIterator::CHILD_ARRAYS_ONLY);
        $original = iterator_to_array(new \RecursiveIteratorIterator($iterator));

        $iterator = new RecursiveSortIterator($iterator);
        $result = iterator_to_array(new \RecursiveIteratorIterator($iterator));

        $this->assertEquals($expected, $result, 'Iterator was not cloned correctly.');
    }

    public function testRecursiveSortIteratorException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $iterator = new RecursiveSortIterator(new \ArrayIterator([]));
    }
}
