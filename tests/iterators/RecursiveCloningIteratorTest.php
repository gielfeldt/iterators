<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\RecursiveCloningIterator;

class RecursiveCloningIteratorTest extends IteratorsTestBase
{
    public function testCloningIterator()
    {
        $object1 = (object) ['object1' => 'value1'];
        $object2 = (object) ['object2' => 'value2'];
        $object3 = (object) ['object3' => 'value3'];
        $objectc1 = (object) ['object1' => 'value1'];
        $objectc2 = (object) ['object2' => 'value2'];
        $objectc3 = (object) ['object3' => 'value3'];

        $input = [
            0 => $object1,
            1 => $object2,
            2 => [
                3 => $object3,
            ],
            4 => 'test1',
        ];
        $expected = [
            0 => $objectc1,
            1 => $objectc2,
            3 => $objectc3,
            4 => 'test1',
        ];

        $iterator = new \RecursiveArrayIterator($input, \RecursiveArrayIterator::CHILD_ARRAYS_ONLY);
        $original = iterator_to_array(new \RecursiveIteratorIterator($iterator));

        $iterator = new RecursiveCloningIterator($iterator);
        $result = iterator_to_array(new \RecursiveIteratorIterator($iterator));

        $this->assertEquals($original, $result, 'Iterator was not cloned correctly.');
        $this->assertEquals($expected, $result, 'Iterator was not cloned correctly.');

        $object1->object1 = 'newvalue1';
        $this->assertNotEquals($original, $result, 'Iterator was not cloned correctly.');
        $this->assertEquals($expected, $result, 'Iterator was not cloned correctly.');
    }

    public function testRecursiveCloningIteratorException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $iterator = new RecursiveCloningIterator(new \ArrayIterator([]));
    }
}
