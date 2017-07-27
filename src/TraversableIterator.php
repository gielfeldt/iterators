<?php

namespace Gielfeldt\Iterators;

class TraversableIterator extends \IteratorIterator
{
    private $index;

    public function __construct(iterable $iterator)
    {
        parent::__construct(Iterator::iterableToIterator($iterator));
    }

    public function rewind()
    {
        $this->index = 0;
        return parent::rewind();
    }

    public function next()
    {
        $this->index++;
        return parent::next();
    }

    public function getIndex()
    {
        return $this->index;
    }
}
