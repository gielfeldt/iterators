<?php

namespace Gielfeldt\Iterators;

class RecursiveCloningIterator extends CloningIterator implements \RecursiveIterator
{
    public function __construct($iterator)
    {
        parent::__construct($iterator);
        if (!$this->getInnerIterator() instanceof \RecursiveIterator) {
            throw new \InvalidArgumentException('An instance of RecursiveIterator or IteratorAggregate creating it is required');
        }
    }

    public function hasChildren()
    {
        return $this->getInnerIterator()->hasChildren();
    }

    public function getChildren()
    {
        return new self($this->getInnerIterator()->getChildren());
    }
}
