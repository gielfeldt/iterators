<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\ClonedIterator;

class ClonedIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testClonedIterator()
    {
        $object1 = (object) ['object1' => 'value1'];
        $object2 = (object) ['object2' => 'value2'];
        $object3 = (object) ['object3' => 'value3'];
        $objectc1 = (object) ['object1' => 'value1'];
        $objectc2 = (object) ['object2' => 'value2'];
        $objectc3 = (object) ['object3' => 'value3'];

        $input = [$object1, $object2, $object3, 'test1'];
        $expected = [$objectc1, $objectc2, $objectc3, 'test1'];

        $iterator = new \ArrayIterator($input);
        $original = iterator_to_array($iterator);

        $iterator = new ClonedIterator($iterator);
        $result = iterator_to_array($iterator);

        $this->assertEquals($input, $original, 'Iterator was not cloned correctly.');
        $this->assertEquals($input, $result, 'Iterator was not cloned correctly.');
        $this->assertEquals($expected, $result, 'Iterator was not cloned correctly.');

        $object1->object1 = 'newvalue1';
        $this->assertEquals($input, $original, 'Iterator was not cloned correctly.');
        $this->assertNotEquals($input, $result, 'Iterator was not cloned correctly.');
        $this->assertEquals($expected, $result, 'Iterator was not cloned correctly.');

    }
}
