<?php

namespace Gielfeldt\Iterators;

class InfiniteIterator extends RepeatIterator
{
    public function __construct(\Traversable $iterator, callable $endCondition = null)
    {
        $this->endCondition = $endCondition ? \Closure::fromCallable($endCondition) : null;
        parent::__construct($iterator, INF);
    }

    public function valid()
    {
        $valid = parent::valid();
        return $this->endCondition ? ($this->endCondition)($this) : $valid;
    }
}
