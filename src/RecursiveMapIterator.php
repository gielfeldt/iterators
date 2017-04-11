<?php

namespace Gielfeldt\Iterators;

class RecursiveMapIterator extends MapIterator implements \RecursiveIterator
{
    public function __construct(\Traversable $iterator, callable $callback)
    {
        parent::__construct($iterator, $callback);
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
        return new self($this->getInnerIterator()->getChildren(), $this->callback);
    }
}
