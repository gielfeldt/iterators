<?php

namespace Gielfeldt\Iterators;

class InfiniteIterator extends IteratorIterator
{
    private $endCondition;
    private $currentIteration;

    public function __construct(\Traversable $iterator, callable $endCondition = null)
    {
        $this->endCondition = $endCondition ? \Closure::fromCallable($endCondition) : null;
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
    }

    public function valid()
    {
        if (!parent::valid()) {
            parent::rewind();
            $this->currentIteration++;
        }

        return $this->endCondition ? !($this->endCondition)($this) : parent::valid();
    }
}
