<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\ZipIterator;
use Gielfeldt\Iterators\ValuesIterator;

class ZipIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testZipIterator()
    {
        $input1 = [1, 4, 7, 10, 13];
        $input2 = [2, 5, 8, 11];
        $input3 = [3, 6, 9, 12, 14];
        $expected1 = [3, 6, 9, 12, 14];
        $expected2 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];

        $iterator = new ZipIterator(
            new \ArrayIterator($input1),
            new \ArrayIterator($input2),
            new \ArrayIterator($input3)
        );

        $this->assertEquals($expected1, iterator_to_array($iterator), 'Iterator was not zipped correctly.');
        $this->assertEquals($expected2, iterator_to_array(new ValuesIterator($iterator)), 'Iterator was not zipped correctly.');
    }
}
