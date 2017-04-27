<?php

namespace Gielfeldt\Iterators;

class InfiniteIterator extends TraversableIterator
{
    private $empty = false;
    private $currentIteration;

    public function __construct(\Traversable $iterator)
    {
        parent::__construct($iterator);
    }

    public function getCurrentIteration()
    {
        return $this->currentIteration;
    }

    public function rewind()
    {
        $this->currentIteration = 0;
        parent::rewind();
        $this->empty = !parent::valid();
    }

    public function valid()
    {
        if (!$this->empty && !parent::valid()) {
            parent::rewind();
            $this->currentIteration++;
        }

        return !$this->empty;
    }
}
