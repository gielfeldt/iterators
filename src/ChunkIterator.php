<?php

namespace Gielfeldt\Iterators;

class ChunkIterator extends EventIterator
{
    protected $size;
    protected $innerIterator;

    public function __construct(\Traversable $iterator, $size)
    {
        $this->size = $size;
        $this->innerIterator = $iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator);
        parent::__construct(new \ArrayIterator());
    }

    public function rewind()
    {
        $this->innerIterator->rewind();
        $this->setInnerIterator(new \ArrayIterator());
        if ($this->innerIterator->valid()) {
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

    public function next()
    {
        $current = $this->current();
        while ($current->valid()) {
            $current->next();
        }
        parent::next();
    }
}
