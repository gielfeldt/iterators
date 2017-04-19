<?php

namespace Gielfeldt\Iterators;

class FiniteIterator extends \IteratorIterator
{
    public function __construct(\Traversable $iterator, callable $endCondition = null)
    {
        $this->endCondition = $endCondition ? \Closure::fromCallable($endCondition) : null;
        parent::__construct($iterator);
    }

    public function valid()
    {
        return $this->endCondition ? ($this->endCondition)($this) : parent::valid();
    }
}
