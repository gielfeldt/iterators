<?php

namespace Gielfeldt\Iterators;

class RecursiveUniqueIterator extends UniqueIterator implements \RecursiveIterator
{
    protected $flags;

    public function __construct(\Traversable $iterator, int $flags = 0, callable $callback = self::UNIQUE_CURRENT)
    {
        parent::__construct($iterator, $flags, $callback);
        if (!$this->getInnerIterator() instanceof \RecursiveIterator) {
            throw new \InvalidArgumentException('An instance of RecursiveIterator or IteratorAggregate creating it is required');
        }
        $this->flags = $flags;
    }

    public function hasChildren()
    {
        return $this->getInnerIterator()->hasChildren();
    }

    public function getChildren()
    {
        return new self($this->getInnerIterator()->getChildren(), $this->flags, $this->callback);
    }
}
