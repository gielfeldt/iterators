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
            $nr = new \NoRewindIterator($this->innerIterator);
            $l = new \LimitIterator($nr, 0, $this->size);
            $e = new EventIterator($l);

            $e->onFinished(function ($iterator, $callback) use ($nr) {
                $iterator->onFinished(null);
                $l = new \LimitIterator($nr, 0, $this->size);
                $l->rewind();
                if ($l->valid()) {
                    $e = new EventIterator($l);
                    $e->onFinished($callback);
                    $e->rewind();
                    $this->append($e);
                }
                return false;
            });
            $this->append($e);
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
