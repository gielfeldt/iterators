<?php

namespace Gielfeldt\Iterators;

class RecursiveMapIterator extends MapIterator implements \RecursiveIterator
{
    use MultipleTypeHintingTrait;

    public function __construct(\Traversable $iterator, callable $callback)
    {
        $this->checkTypeHinting($iterator, \RecursiveIterator::class, \IteratorAggregate::class);
        $iterator = $iterator instanceof \IteratorAggregate ? $iterator->getIterator() : $iterator;
        $this->checkTypeHinting($iterator, \RecursiveIterator::class);
        parent::__construct($iterator, $callback);
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
