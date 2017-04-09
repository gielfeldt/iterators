<?php

namespace Gielfeldt\Iterators;

class MapIterator extends \IteratorIterator
{
    public function __construct(\Traversable $iterator, callable $callback)
    {
        parent::__construct($iterator);
        $this->callback = \Closure::fromCallable($callback);
    }

    public function current()
    {
        $current = parent::current();
        return $current ? ($this->callback)($current) : $current;
    }
}
