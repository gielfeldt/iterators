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
class ChunkIterator extends EventIterator
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
    public function __construct(\Traversable $iterator, $size)
    {
        $this->size = $size;
        $this->innerIterator = $iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator);
        parent::__construct(new \ArrayIterator());
    }

    /**
     * Rewind the original iterator and setup self appending iterator for the
     * chunks.
     *
     * @see \Iterator::rewind().
     */
    public function rewind()
    {
        $this->innerIterator->rewind();

        // We start of with an empty array of chunks.
        $this->setInnerIterator(new \ArrayIterator());

        // Only add a chunk if the original iterator is not empty.
        if ($this->innerIterator->valid()) {
            // Each chunk is a limited non-rewindable iterator of the original
            // iterator.
            // An onFinished event is attached to the chunk, so that it append
            // the next iterator chunk to the outer iterator.
            $innerIterator = new \NoRewindIterator($this->innerIterator);
            $limitedInnerIterator = new \LimitIterator($innerIterator, 0, $this->size);
            $eventIterator = new EventIterator($limitedInnerIterator);

            $eventIterator->onFinished(function ($iterator, $callback) use ($innerIterator) {
                $iterator->onFinished(null);
                $limitedInnerIterator = new \LimitIterator($innerIterator, 0, $this->size);
                $limitedInnerIterator->rewind();
                if ($limitedInnerIterator->valid()) {
                    $eventIterator = new EventIterator($limitedInnerIterator);
                    $eventIterator->onFinished($callback);
                    $eventIterator->rewind();
                    $this->append($eventIterator);
                }
                return false;
            });
            $this->append($eventIterator);
        }
    }

    /**
     * @see \Iterator::next()
     */
    public function next()
    {
        // Finish iteration on the current chunk, if necessary, in order to
        // trigger the onFinished event, which sets up the next chunk.
        $current = $this->current();
        while ($current->valid()) {
            $current->next();
        }
        parent::next();
    }
}
