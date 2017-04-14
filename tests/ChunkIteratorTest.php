<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\ChunkIterator;
use Gielfeldt\Iterators\ValuesIterator;

class ChunkIteratorTest extends IteratorsTestBase
{
    public function testChunkIterator()
    {
        $input = new \ArrayIterator(range(1, 20));
        $iterator = new ChunkIterator($input, 4);

        $iterator->rewind();
        $this->assertEquals(range(1, 4), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 0 not correct.');

        $iterator->next();
        $this->assertEquals(range(5, 8), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 1 not correct.');

        $iterator->next();
        $this->assertEquals(range(9, 12), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 2 not correct.');

        $iterator->next();
        $this->assertEquals(range(13, 16), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 3 not correct.');

        $iterator->next();
        $this->assertEquals(range(17, 20), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 4 not correct.');

        $iterator->next();
        $this->assertFalse($iterator->valid(), 'Chunking did not end.');
    }

    public function testChunkIteratorPartial()
    {
        $input = new \ArrayIterator(range(1, 20));
        $iterator = new ChunkIterator($input, 4);

        $iterator->rewind();
        $this->assertEquals(range(1, 4), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 0 not correct.');

        $iterator->next();
        $this->assertEquals(range(5, 8), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 1 not correct.');

        $iterator->next();
        $this->assertEquals(range(9, 10), iterator_to_array(new ValuesIterator(new \LimitIterator($iterator->current(), 0, 2))), 'Partial chunk 2 not correct.');

        $iterator->next();
        $this->assertEquals(range(13, 16), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 3 not correct.');

        $iterator->next();
        $this->assertEquals(range(17, 20), iterator_to_array(new ValuesIterator($iterator->current())), 'Chunk 4 not correct.');

        $iterator->next();
        $this->assertFalse($iterator->valid(), 'Chunking did not end.');
    }
}
