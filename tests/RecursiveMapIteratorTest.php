<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\RecursiveMapIterator;

class RecursiveMapIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testMapIterator()
    {
        $input = [
            0 => 'test1',
            2 => 'test4',
            [
                4 => 'test2',
                6 => 'test3',
            ],
        ];
        $expected = [
            0 => 'value:test1',
            2 => 'value:test4',
            4 => 'value:test2',
            6 => 'value:test3',
        ];

        $iterator = new \RecursiveIteratorIterator(new RecursiveMapIterator(new \RecursiveArrayIterator($input), [$this, 'mapFunction']));
        $this->assertEquals($expected, iterator_to_array($iterator), 'Iterator was not mapped correctly.');
    }

    public function mapFunction($iterator)
    {
        $current = $iterator->current();
        $current = is_scalar($current) ? "value:$current" : $current;
        $number = str_replace('test', '', $current);
        return [$iterator->key(), $current];
    }

    public function testRecursiveMapIteratorException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $iterator = new RecursiveMapIterator(new \ArrayIterator([]), function ($iterator) {});
    }
}
