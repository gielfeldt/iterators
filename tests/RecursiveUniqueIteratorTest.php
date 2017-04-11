<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\MapIterator;
use Gielfeldt\Iterators\RecursiveUniqueIterator;

class RecursiveUniqueIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testRecursiveUniqueIteratorAssumeUnsorted()
    {
        $input = [
            '0:test1',
            '0:test2',
            '0:test6',
            '0:test2',
            '0:test3' => [
                '1:test4',
                '1:test5',
                '1:test6',
                '1:test6',
                '1:test7' => [
                    '2:test8',
                    '2:test9' => [
                        '3:test10',
                    ],
                ],
            ],
        ];

        $expected = [
            '0:test1',
            '0:test2',
            '0:test6',
            '1:test4',
            '1:test5',
            '1:test6',
            '2:test8',
            '3:test10',
            'a:1:{i:0;s:8:"3:test10";}',
            'a:2:{i:0;s:7:"2:test8";s:7:"2:test9";a:1:{i:0;s:8:"3:test10";}}',
            'a:5:{i:0;s:7:"1:test4";i:1;s:7:"1:test5";i:2;s:7:"1:test6";i:3;s:7:"1:test6";s:7:"1:test7";a:2:{i:0;s:7:"2:test8";s:7:"2:test9";a:1:{i:0;s:8:"3:test10";}}}',
        ];

        $iterator = new \RecursiveIteratorIterator(new RecursiveUniqueIterator(new \RecursiveArrayIterator($input)), \RecursiveIteratorIterator::CHILD_FIRST);

        $result = iterator_to_array(new MapIterator($iterator, function ($iterator) {
            $i = str_repeat(' ', $iterator->getDepth());
            static $key = 0;
            $value = $iterator->current();
            $value = is_scalar($value) ? $value : serialize($value);
            return [$key++, $value];
        }));

        $this->assertEquals($expected, $result, 'Iterator was not filtered correctly.');
    }

    public function testRecursiveUniqueIteratorException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $iterator = new RecursiveUniqueIterator(new \ArrayIterator([]));
    }
}
