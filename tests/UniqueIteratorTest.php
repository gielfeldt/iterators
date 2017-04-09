<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\UniqueIterator;
use Gielfeldt\Iterators\FlipIterator;

class UniqueIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testUniqueIteratorAssumeUnsorted()
    {
        $input = [1, 5, 4, 7, 2, 1, 8, 9, 4, 4, 4, 5, 2];
        $expected = [1, 5, 4, 7, 2, 6 => 8, 9];

        $iterator = new UniqueIterator(new \ArrayIterator($input));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not filtered correctly.');
    }

    public function testUniqueIteratorAssumeSortedButIsUnsorted()
    {
        $input = [1, 5, 4, 7, 2, 1, 8, 9, 4, 4, 4, 5, 2];
        $expected = [1, 5, 4, 7, 2, 1, 8, 9, 4, 11 => 5, 2];

        $iterator = new UniqueIterator(new \ArrayIterator($input), UniqueIterator::ASSUME_SORTED);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not filtered correctly.');
    }

    public function testUniqueIteratorAssumeSortedAndIsSorted()
    {
        $input = [1, 1, 2, 2, 4, 4, 4, 4, 5, 5, 7, 8, 9];
        $expected = [1, 2=> 2, 4 => 4, 8 => 5, 10 => 7, 8, 9];

        $iterator = new UniqueIterator(new \ArrayIterator($input), UniqueIterator::ASSUME_SORTED);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not filtered correctly.');
    }

    public function testUniqueIteratorKeyAssumeUnsorted()
    {
        $input = [1, 5, 4, 7, 2, 1, 8, 9, 4, 4, 4, 5, 2];
        $expected = [1, 5, 4, 7, 2, 6 => 8, 9];

        $iterator = new FlipIterator(new UniqueIterator(new FlipIterator(new \ArrayIterator($input)), 0, UniqueIterator::UNIQUE_KEY));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not filtered correctly.');
    }

    public function testUniqueIteratorKeyAssumeSortedButIsUnsorted()
    {
        $input = [1, 5, 4, 7, 2, 1, 8, 9, 4, 4, 4, 5, 2];
        $expected = [1, 5, 4, 7, 2, 1, 8, 9, 4, 11 => 5, 2];

        $iterator = new FlipIterator(new UniqueIterator(new FlipIterator(new \ArrayIterator($input)), UniqueIterator::ASSUME_SORTED, UniqueIterator::UNIQUE_KEY));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not filtered correctly.');
    }

    public function testUniqueIteratorKeyAssumeSortedAndIsSorted()
    {
        $input = [1, 1, 2, 2, 4, 4, 4, 4, 5, 5, 7, 8, 9];
        $expected = [1, 2=> 2, 4 => 4, 8 => 5, 10 => 7, 8, 9];

        $iterator = new FlipIterator(new UniqueIterator(new FlipIterator(new \ArrayIterator($input)), UniqueIterator::ASSUME_SORTED, UniqueIterator::UNIQUE_KEY));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not filtered correctly.');
    }
}
