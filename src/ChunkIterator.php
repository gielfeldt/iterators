<?php

namespace Gielfeldt\Iterators;

/**
 * Split an iterator into multiple iterators.
 *
 * Usage:
 * foreach (new ChunkIterator($someIterator, 4) as $chunk) {
 *    foreach ($chunk as $key => $value) {
 *       ... do stuff
 *    }
 * }
 */

class ChunkIterator extends ValuesIterator
{
    /**
     * Size of a chunk
     * @var int
     */
    protected $size;

    /**
     * The original iterator to split into chunks.
     * @var \Iterator
     */
    protected $innerIterator;

    /**
     * Constructor.
     *
     * @param \Traversable $iterator
     *   The iterator to split into chunks.
     * @param int $size
     *   The size of each chunk.
     */
    public function __construct(\Traversable $iterator, int $size)
    {
        $this->innerIterator = $iterator;
        $this->size = $size;

        // The outer iterator is a finite replaceable iterator with a condition
        // on the inner iterator not being empty.
        parent::__construct(new InfiniteIterator(new ReplaceableIterator(), function ($iterator) {
            return $iterator->current()->valid();
        }));
    }

    /**
     * Rewind original inner iterator, and recreate inner and outer iterator.
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        // Setup chunked inner iterator.
        $this->innerIterator->rewind();
        $innerIterator = new \NoRewindIterator($this->innerIterator);
        $limitedInnerIterator = new \LimitIterator($innerIterator, 0, $this->size);
        $limitedInnerIterator->rewind();

        // Setup outer iterator.
        $outerIterator = new \ArrayIterator([$limitedInnerIterator]);
        $this->setInnerIterator($outerIterator);
        parent::rewind();
    }

    /**
     * Make sure that the inner iterator is positioned properly, before skipping
     * to next chunk.
     *
     * @see Iterator::next()
     */
    public function next()
    {
        // Finish iteration on the current chunk, if necessary, in order to
        // trigger the onFinished event, which sets up the next chunk.
        while ($this->current()->valid()) {
            $this->current()->next();
        }

        // Check if next chunk has any values.
        $this->current()->rewind();
        return parent::next();
    }
}
