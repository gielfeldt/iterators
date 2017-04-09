<?php

namespace Gielfeldt\Iterators;

class RecursiveSortIterator extends SortIterator implements \RecursiveIterator
{
    protected $children = [];

    public function __construct(\RecursiveIterator $iterator, callable $callback = self::SORT_CURRENT, int $direction = self::SORT_ASC, int $flags = 0)
    {
        parent::__construct($iterator, $callback, $direction, $flags);
    }

    protected function addElement($key, $value)
    {
        if ($this->iterator->hasChildren()) {
            $this->children[$key] = $this->iterator->getChildren();
        }
        parent::addElement($key, $value);
    }

    public function hasChildren()
    {
        return isset($this->children[$this->key()]);
    }

    public function getChildren()
    {
        return new self($this->children[$this->key()], $this->callback, self::SORT_DESC, $this->flags);
    }
}
