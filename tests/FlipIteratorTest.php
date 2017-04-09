<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\FlipIterator;

class FlipIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testFlipIterator()
    {
        $input = [0 => 'test1', 1 => 'test3', 2 => 'test2'];
        $expected = ['test1' => 0, 'test2' => 2, 'test3' => 1];

        $iterator = new FlipIterator(new \ArrayIterator($input));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not flipped correctly.');
    }
}
