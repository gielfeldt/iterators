<?php

namespace Gielfeldt\Iterators;

class RecursiveClonedIterator extends ClonedIterator implements \RecursiveIterator
{
    use MultipleTypeHintingTrait;

    public function __construct($iterator)
    {
        $this->checkTypeHinting($iterator, \RecursiveIterator::class, \IteratorAggregate::class);
        $iterator = $iterator instanceof \IteratorAggregate ? $iterator->getIterator() : $iterator;
        $this->checkTypeHinting($iterator, \RecursiveIterator::class);
        parent::__construct($iterator);
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
