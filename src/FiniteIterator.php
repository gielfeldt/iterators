<?php

namespace Gielfeldt\Iterators;

class FiniteIterator extends InfiniteIterator
{
    protected $endCondition;

    public function __construct(\Traversable $iterator, callable $endCondition, $flags = 0)
    {
        $this->endCondition = \Closure::fromCallable($endCondition);
        parent::__construct($iterator, $flags);
    }

    public function valid()
    {
        return ($this->endCondition)($this);
    }
}
