<?php

namespace Gielfeldt\Iterators;

class RecursiveUniqueIterator extends UniqueIterator implements \RecursiveIterator
{
    use MultipleTypeHintingTrait;

    public function __construct(\Traversable $iterator, callable $callback = self::UNIQUE_CURRENT, int $flags = 0)
    {
        $this->checkTypeHinting($iterator, \RecursiveIterator::class, \IteratorAggregate::class);
        $iterator = $iterator instanceof \IteratorAggregate ? $iterator->getIterator() : $iterator;
        $this->checkTypeHinting($iterator, \RecursiveIterator::class);
        parent::__construct($iterator, $callback, $flags);
        $this->flags = $flags;
    }

    public function hasChildren()
    {
        return $this->getInnerIterator()->hasChildren();
    }

    public function getChildren()
    {
        return new self($this->getInnerIterator()->getChildren(), $this->callback, $this->flags);
    }
}
