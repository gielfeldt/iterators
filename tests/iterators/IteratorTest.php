<?php
namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\Iterator;
use Gielfeldt\Iterators\ChunkIterator;

class IteratorTest extends IteratorsTestBase
{
    public function testInstanceOf()
    {
        $iterator = new \ArrayIterator();

        $this->assertTrue(Iterator::instanceOf($iterator, \ArrayIterator::class), 'Iterator should be an instance of ArrayIterator');
        $this->assertFalse(Iterator::instanceOf($iterator, \EmptyIterator::class), 'Iterator should not be an instance of EmptyIterator');

        $wrapped = new \IteratorIterator($iterator);
        $this->assertTrue(Iterator::instanceOf($wrapped, \ArrayIterator::class), 'Wrapped iterator should be an instance of ArrayIterator');
        $this->assertFalse(Iterator::instanceOf($wrapped, \EmptyIterator::class), 'Wrapped iterator should not be an instance of EmptyIterator');

        $doublewrapped = new \IteratorIterator($wrapped);
        $this->assertTrue(Iterator::instanceOf($doublewrapped, \ArrayIterator::class), 'Double wrapped iterator should be an instance of ArrayIterator');
        $this->assertFalse(Iterator::instanceOf($doublewrapped, \EmptyIterator::class), 'Double wrapped iterator should not be an instance of EmptyIterator');
    }

    public function testGetInnerIterators()
    {
        $iterator = new \ArrayIterator();

        $iterators = Iterator::getInnerIterators($iterator);
        $this->assertCount(0, $iterators, 'ArrayIterator have no inner iterator.');

        $wrapped = new \IteratorIterator($iterator);
        $iterators = Iterator::getInnerIterators($wrapped);
        $this->assertCount(1, $iterators, 'Wrapped iterator should have one inner.');

        $doublewrapped = new \IteratorIterator($wrapped);
        $iterators = Iterator::getInnerIterators($doublewrapped);
        $this->assertCount(2, $iterators, 'Double wrapped iterator should have two inner iterators.');
    }

    public function testGetInnerIteratorsIncludeSelf()
    {
        $iterator = new \ArrayIterator();

        $iterators = Iterator::getInnerIterators($iterator, true);
        $this->assertCount(1, $iterators, 'ArrayIterator should be the innermost iterator.');

        $wrapped = new \IteratorIterator($iterator);
        $iterators = Iterator::getInnerIterators($wrapped, true);
        $this->assertCount(2, $iterators, 'Wrapped iterator should have one inner iterator plus itself.');

        $doublewrapped = new \IteratorIterator($wrapped);
        $iterators = Iterator::getInnerIterators($doublewrapped, true);
        $this->assertCount(3, $iterators, 'Double wrapped iterator should have two inner iterators plus itself.');
    }

    public function testGetInnermostIterator()
    {
        $iterator = new \ArrayIterator();

        $wrapped = new \IteratorIterator($iterator);
        $innermostIterator = Iterator::getInnermostIterator($wrapped);
        $this->assertEquals($iterator, $innermostIterator, 'Innermost iterator is incorrect.');

        $doublewrapped = new \IteratorIterator($wrapped);
        $innermostIterator = Iterator::getInnermostIterator($doublewrapped);
        $this->assertEquals($iterator, $innermostIterator, 'Innermost iterator is incorrect.');
    }

    public function reduceMin($carry, $current, $key)
    {
        return $carry < $current ? $carry : $current;
    }

    public function testReduce()
    {
        $input = [-45, 1, 2, 45, 3, 4, 5, 6];
        $expected = -45;

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::reduce($iterator, [$this, 'reduceMin'], INF), 'Iterator min is not correct.');
    }

    public function testSum()
    {
        $input = [1, 2, 3, 4, 5, 6];
        $expected = 21;

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::sum($iterator), 'Iterator sum is not correct.');
    }

    public function testProduct()
    {
        $input = [1, 2, 3, 4, 5, 6];
        $expected = 720;

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::product($iterator), 'Iterator product is not correct.');
    }

    public function testAverage()
    {
        $input = [1, 2, 3, 4, 5, 6];
        $expected = 3.5;

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::average($iterator), 'Iterator average is not correct.');
    }

    public function testMin()
    {
        $input = [1, 2, 3, 4, 5, 6];
        $expected = 1;

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::min($iterator), 'Iterator min is not correct.');
    }

    public function testMax()
    {
        $input = [1, 2, 3, 4, 5, 6];
        $expected = 6;

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::max($iterator), 'Iterator max is not correct.');
    }

    public function testConcatenate()
    {
        $input = [1, 2, 3, 4, 5, 6];
        $expected = "123456";

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::concatenate($iterator), 'Iterator concatenation is not correct.');
    }

    public function testImplode()
    {
        $input = [1, 2, 3, 4, 5, 6];
        $expected = "1,2,3,4,5,6";

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::implode(',', $iterator), 'Iterator implosion is not correct.');
    }

    public function testIteratorToArray()
    {
        $input = new \ArrayIterator(range(1, 20));
        $iterator = new ChunkIterator($input, 4);

        $expected = [
            [1, 2, 3, 4],
            [5, 6, 7, 8],
            [9, 10, 11, 12],
            [13, 14, 15, 16],
            [17, 18, 19, 20],
        ];

        $result = Iterator::iterator_to_array_deep($iterator, false);

        $this->assertEquals($expected, $result, 'Iterator not converted properly.');

        $input = new \ArrayIterator(range(1, 20));
        $iterator = new ChunkIterator($input, 4);

        $expected = [
            [1, 2, 3, 4],
            [4 => 5, 6, 7, 8],
            [8 => 9, 10, 11, 12],
            [12 => 13, 14, 15, 16],
            [16 => 17, 18, 19, 20],
        ];

        $result = Iterator::iterator_to_array_deep($iterator);

        $this->assertEquals($expected, $result, 'Iterator not converted properly.');
    }
}
