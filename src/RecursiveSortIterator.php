<?php

namespace Gielfeldt\Iterators;

class RecursiveSortIterator extends SortIterator implements \RecursiveIterator
{
    protected $children = [];

    public function __construct(\Traversable $iterator, int $direction = self::SORT_ASC, int $flags = 0, callable $callback = self::SORT_CURRENT)
    {
        parent::__construct($iterator, $direction, $flags, $callback);
        if (!$this->getInnerIterator() instanceof \RecursiveIterator) {
            throw new \InvalidArgumentException('An instance of RecursiveIterator or IteratorAggregate creating it is required');
        }
    }

    protected function generateElement($key, $value, $iterator)
    {
        if ($iterator->hasChildren()) {
            $this->children[$key] = $iterator->getChildren();
        }
        parent::generateElement($key, $value, $iterator);
    }

    public function hasChildren()
    {
        return isset($this->children[$this->key()]);
    }

    public function getChildren()
    {
        return new self($this->children[$this->key()], $this->direction, $this->flags, $this->callback);
    }
}
