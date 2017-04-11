<?php
namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\Iterator;

class IteratorTest extends \PHPUnit_Framework_TestCase
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

    public function reduceMax($carry, $current, $key)
    {
        return $carry > $current ? $carry : $current;
    }

    public function testReduce()
    {
        $input = [1, 2, 45, 3, 4, 5, 6];
        $expected = 45;

        $iterator = new \ArrayIterator($input);
        $this->assertEquals($expected, Iterator::reduce($iterator, [$this, 'reduceMax']), 'Iterator max is not correct.');
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

    public function testChunkCountable()
    {
        $input = [1, 2, 3, 4, 5, 6, 7];

        $iterator = new \ArrayIterator($input);
        $chunks = Iterator::chunk($iterator, 2);
        $this->assertCount(4, $chunks, 'Iterator chunk count is not correct.');

        $this->assertCount(2, $chunks[0], 'Iterator chunk 0 count is not correct.');
        $this->assertCount(2, $chunks[1], 'Iterator chunk 1 count is not correct.');
        $this->assertCount(2, $chunks[2], 'Iterator chunk 2 count is not correct.');
        $this->assertCount(1, $chunks[3], 'Iterator chunk 3 count is not correct.');
    }

    public function testChunkNonCountable()
    {
        $input = [1, 2, 3, 4, 5, 6, 7];

        $iterator = new \ArrayIterator($input);
        $iterator = new \IteratorIterator($iterator);
        $chunks = Iterator::chunk($iterator, 2);
        $this->assertCount(4, $chunks, 'Iterator chunk count is not correct.');

        $this->assertCount(2, $chunks[0], 'Iterator chunk 0 count is not correct.');
        $this->assertCount(2, $chunks[1], 'Iterator chunk 1 count is not correct.');
        $this->assertCount(2, $chunks[2], 'Iterator chunk 2 count is not correct.');
        $this->assertCount(1, $chunks[3], 'Iterator chunk 3 count is not correct.');
    }

}
