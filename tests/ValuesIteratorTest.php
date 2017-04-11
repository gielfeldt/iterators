<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\ValuesIterator;

class ValuesIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testValuesIterator()
    {
        $input = [
            'test1',
            'test2',
            [
                'test3',
                'test4',
            ]
        ];
        $expected1 = [
            'test3',
            'test4',
        ];
        $expected2 = [
            'test1',
            'test2',
            'test3',
            'test4',
        ];

        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($input));
        $result = new ValuesIterator($iterator);
        $this->assertEquals($expected1, iterator_to_array($iterator), 'Iterator was not processed correctly.');
        $this->assertEquals($expected2, iterator_to_array($result), 'Iterator was not processed correctly.');
    }
}
