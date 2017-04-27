<?php

namespace Gielfeldt\Iterators;

class IndexIterator extends TraversableIterator
{
    private $indexingIterator;
    private $valid;

    public function __construct(\Traversable $iterator, \Traversable $indexingIterator)
    {
        $this->indexingIterator = $indexingIterator instanceof \Iterator ? $indexingIterator : new \IteratorIterator($indexingIterator);
        parent::__construct($iterator);
    }

    public function rewind()
    {
        parent::rewind();
        $this->indexingIterator->rewind();
        $this->next();
    }

    public function next()
    {
        $this->valid = $this->indexingIterator->valid();
        $index = $this->indexingIterator->current();
        while (parent::valid() && $this->getIndex() < $index) {
            parent::next();
        }
        $this->indexingIterator->next();
    }

    public function valid()
    {
        return $this->valid && parent::valid();
    }
}
