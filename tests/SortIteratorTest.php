<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\SortIterator;

class SortIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testSortNoReindexAsc()
    {
        $input = [0 => 'test1', 1 => 'test3', 2 => 'test2'];
        $expected = [0 => 'test1', 2 => 'test2', 1 => 'test3'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_ASC);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortNoReindexDesc()
    {
        $input = [0 => 'test1', 1 => 'test3', 2 => 'test2'];
        $expected = [1 => 'test3', 2 => 'test2', 0 => 'test1'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortReindexAsc()
    {
        $input = ['test1', 'test3', 'test2'];
        $expected = ['test1', 'test2', 'test3'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_ASC, SortIterator::SORT_REINDEX);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortReindexDesc()
    {
        $input = ['test1', 'test3', 'test2'];
        $expected = ['test3', 'test2', 'test1'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC, SortIterator::SORT_REINDEX);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortKeyNoReindexAsc()
    {
        $input = ['test1' => 'test1', 'test3' => 'test3', 'test2' => 'test2'];
        $expected = ['test1' => 'test1', 'test2' => 'test2', 'test3' => 'test3'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_ASC, 0, SortIterator::SORT_KEY);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortKeyNoReindexDesc()
    {
        $input = ['test1' => 'test1', 'test3' => 'test3', 'test2' => 'test2'];
        $expected = ['test3' => 'test3', 'test2' => 'test2', 'test1' => 'test1'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC, 0, SortIterator::SORT_KEY);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortKeyReindexAsc()
    {
        $input = ['test1' => 'test9', 'test3' => 'test8', 'test2' => 'test7'];
        $expected = ['test9', 'test7', 'test8'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_ASC, SortIterator::SORT_REINDEX, SortIterator::SORT_KEY);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortKeyReindexDesc()
    {
        $input = ['test1' => 'test9', 'test3' => 'test8', 'test2' => 'test7'];
        $expected = ['test8', 'test7', 'test9'];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC, SortIterator::SORT_REINDEX, SortIterator::SORT_KEY);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function compareBySqare($a, $b)
    {
        return ($a->current * $a->current) <=> ($b->current * $b->current);
    }

    public function testSortCustomNoReindexAsc()
    {
        $input = ['test1' => -2, 'test3' => 0, 'test2' => 3];
        $expected = ['test3' => 0, 'test1' => -2, 'test2' => 3];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_ASC, 0, [$this, 'compareBySqare']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortCustomNoReindexDesc()
    {
        $input = ['test1' => -2, 'test3' => 0, 'test2' => 3];
        $expected = ['test2' => 3, 'test1' => -2, 'test3' => 0];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC, 0, [$this, 'compareBySqare']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortCustomReindexAsc()
    {
        $input = ['test1' => -2, 'test3' => 0, 'test2' => 3];
        $expected = [0, -2, 3];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_ASC, SortIterator::SORT_REINDEX, [$this, 'compareBySqare']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testSortCustomReindexDesc()
    {
        $input = ['test1' => -2, 'test3' => 0, 'test2' => 3];
        $expected = [3, -2, 0];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC, SortIterator::SORT_REINDEX, [$this, 'compareBySqare']);
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not sorted correctly.');
    }

    public function testMinMaxFirstLastAsc()
    {
        $input = ['test1' => -2, 'test3' => 0, 'test2' => 3];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_ASC, SortIterator::SORT_REINDEX, [$this, 'compareBySqare']);
        $this->assertEquals(0, $iterator->min(), 'Iterator was not sorted correctly.');
        $this->assertEquals(3, $iterator->max(), 'Iterator was not sorted correctly.');
        $this->assertEquals(0, $iterator->first(), 'Iterator was not sorted correctly.');
        $this->assertEquals(3, $iterator->last(), 'Iterator was not sorted correctly.');
    }

    public function testMinMaxDesc()
    {
        $input = ['test1' => -2, 'test3' => 0, 'test2' => 3];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC, SortIterator::SORT_REINDEX, [$this, 'compareBySqare']);
        $this->assertEquals(0, $iterator->min(), 'Iterator was not sorted correctly.');
        $this->assertEquals(3, $iterator->max(), 'Iterator was not sorted correctly.');
        $this->assertEquals(3, $iterator->first(), 'Iterator was not sorted correctly.');
        $this->assertEquals(0, $iterator->last(), 'Iterator was not sorted correctly.');
    }

    public function testCount()
    {
        $input = ['test1' => -2, 'test3' => 0, 'test2' => 3];

        $iterator = new SortIterator(new \ArrayIterator($input), SortIterator::SORT_DESC, SortIterator::SORT_REINDEX, [$this, 'compareBySqare']);
        $this->assertEquals(3, $iterator->count(), 'Iterator was not sorted correctly.');
    }

}
