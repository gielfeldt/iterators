<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\CombineIterator;

class CombineIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testCombineIteratorAll()
    {
        $input1 = [1, 4, 7, 10, 13];
        $input2 = [3, 6, 9, 12];
        $expected = [1 => 3, 4 => 6, 7 => 9, 10 => 12];

        $iterator = new CombineIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            CombineIterator::NEED_ALL
        );

        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not combined correctly.');

        $input1 = [1, 4, 7, 10];
        $input2 = [3, 6, 9, 12, 15];
        $expected = [1 => 3, 4 => 6, 7 => 9, 10 => 12];

        $iterator = new CombineIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            CombineIterator::NEED_ALL
        );

        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not combined correctly.');

        $input1 = [1, 4, 7, 10, 13];
        $input2 = [3, 6, 9, 12, 15];
        $expected = [1 => 3, 4 => 6, 7 => 9, 10 => 12, 13 => 15];

        $iterator = new CombineIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            CombineIterator::NEED_ALL
        );

        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not combined correctly.');
    }

    public function testCombineIteratorAny()
    {
        $input1 = [1, 4, 7, 10, 13];
        $input2 = [3, 6, 9, 12];
        $expected = [1 => 3, 4 => 6, 7 => 9, 10 => 12, 13 => null];

        $iterator = new CombineIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            CombineIterator::NEED_ANY
        );

        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not combined correctly.');

        $input1 = [1, 4, 7, 10];
        $input2 = [3, 6, 9, 12, 15];
        $expected = [1 => 3, 4 => 6, 7 => 9, 10 => 12, null => 15];

        $iterator = new CombineIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            CombineIterator::NEED_ANY
        );

        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not combined correctly.');

        $input1 = [1, 4, 7, 10, 13];
        $input2 = [3, 6, 9, 12, 15];
        $expected = [1 => 3, 4 => 6, 7 => 9, 10 => 12, 13 => 15];

        $iterator = new CombineIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            CombineIterator::NEED_ANY
        );

        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not combined correctly.');
    }
}
