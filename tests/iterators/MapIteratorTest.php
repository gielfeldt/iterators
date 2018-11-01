<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\MapIterator;

class MapIteratorTest extends IteratorsTestBase
{
    public function testMapIterator()
    {
        // Test simple numeric array.
        $input = ['test1', 'test4', 'test2', 'test3'];
        $expected = ['value:test1', 'value:test4', 'value:test2', 'value:test3'];
        $iterator = new MapIterator(new \ArrayIterator($input), function ($iterator) {
            return 'value:' . $iterator->current();
        });
        $output = iterator_to_array($iterator, true);
        $this->assertEquals($expected, $output, 'Iterator was not mapped correctly.');

        // Test simple numeric array in reverse.
        $input = [3 => 'test1', 2 => 'test4', 1 => 'test2', 0 => 'test3'];
        $expected = ['value:test1', 'value:test4', 'value:test2', 'value:test3'];
        $iterator = new MapIterator(new \ArrayIterator($input), function ($iterator) {
            return 'value:' . $iterator->current();
        });
        $output = iterator_to_array($iterator, true);
        $this->assertEquals($expected, $output, 'Iterator was not mapped correctly.');

        // Test associative array in discarding keys.
        $input = ['key:1' => 'test1', 'key:2' => 'test4', 'key:3' => 'test2', 'key:4' => 'test3'];
        $expected = ['value:test1', 'value:test4', 'value:test2', 'value:test3'];
        $iterator = new MapIterator(new \ArrayIterator($input), function ($iterator) {
            return 'value:' . $iterator->current();
        });
        $output = iterator_to_array($iterator, true);
        $this->assertEquals($expected, $output, 'Iterator was not mapped correctly.');

        // Test associative array in preserving keys.
        $input = ['key:1' => 'test1', 'key:2' => 'test4', 'key:3' => 'test2', 'key:4' => 'test3'];
        $expected = ['key:1' => 'value:test1', 'key:2' => 'value:test4', 'key:3' => 'value:test2', 'key:4' => 'value:test3'];
        $iterator = new MapIterator(new \ArrayIterator($input), function ($iterator) {
            return [$iterator->key(), 'value:' . $iterator->current()];
        });
        $output = iterator_to_array($iterator, true);
        $this->assertEquals($expected, $output, 'Iterator was not mapped correctly.');

        // Test associative/numeric array in preserving keys.
        $input = ['mixed' => 'test1', 'test' => 'test4', 1 => 'test2', 'test3'];
        $expected = ['mixed' => 'value:test1', 'test' => 'value:test4', 1 => 'value:test2', 2 => 'value:test3'];
        $iterator = new MapIterator(new \ArrayIterator($input), function ($iterator) {
            return [$iterator->key(), 'value:' . $iterator->current()];
        });
        $output = iterator_to_array($iterator, true);
        $this->assertEquals($expected, $output, 'Iterator was not mapped correctly.');

        // Test numeric array in adding keys.
        $input = ['test1', 'test4', 'test2', 'test3'];
        $expected = ['key:1' => 'value:test1', 'key:4' => 'value:test4', 'key:2' => 'value:test2', 'key:3' => 'value:test3'];
        $iterator = new MapIterator(new \ArrayIterator($input), function ($iterator) {
            return ['key:' . str_replace('test', '', $iterator->current()), 'value:' . $iterator->current()];
        });
        $output = iterator_to_array($iterator, true);
        $this->assertEquals($expected, $output, 'Iterator was not mapped correctly.');

        // Test numeric array in adding keys and numeric.
        $input = ['test1', 'test4', 'test2', 'test3'];
        $expected = [0 => 'value:test1', 'test' => 'value:test4', 'hest' => 'value:test2', 1 => 'value:test3'];
        $iterator = new MapIterator(new \ArrayIterator($input), function ($iterator) {
            if ($iterator->current() == 'test1') return 'value:' . $iterator->current();
            if ($iterator->current() == 'test4') return ['test', 'value:' . $iterator->current()];
            if ($iterator->current() == 'test2') return ['hest', 'value:' . $iterator->current()];
            if ($iterator->current() == 'test3') return 'value:' . $iterator->current();
        });
        $output = iterator_to_array($iterator, true);
        $this->assertEquals($expected, $output, 'Iterator was not mapped correctly.');
    }
}
