<?php

namespace Gielfeldt\Iterators;

class CountableIterator extends \IteratorIterator implements \Countable
{
    public function __construct(\Traversable $iterator)
    {
        parent::__construct($iterator);
    }

    public function count()
    {
        $count = 0;
        foreach ($this as $v) {
            $count++;
        }
        return $count;
    }
}
