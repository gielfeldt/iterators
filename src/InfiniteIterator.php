<?php

namespace Gielfeldt\Iterators;

class InfiniteIterator extends IteratorIterator
{
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

    public function next()
    {
        parent::next();
        #if (!parent::valid())
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
