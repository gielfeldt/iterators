<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\KeysIterator;

class KeysIteratorTest extends IteratorsTestBase
{
    public function testKeysIterator()
    {
        $input = [
            'test1' => 'test1',
            'test2' => 'test2',
            [
                'test1' => 'test3',
                'test2' => 'test4',
            ]
        ];
        $expected1 = [
            'test1' => 'test3',
            'test2' => 'test4',
        ];
        $expected2 = [
            'test1',
            'test2',
            'test1',
            'test2',
        ];

        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($input));
        $result = new KeysIterator($iterator);
        $this->assertEquals($expected1, iterator_to_array($iterator), 'Iterator was not processed correctly.');
        $this->assertEquals($expected2, iterator_to_array($result), 'Iterator was not processed correctly.');
    }
}
