<?php

namespace Gielfeldt\Iterators;

class RecursiveSortIterator extends SortIterator implements \RecursiveIterator
{
    protected $children = [];

    public function __construct(\Traversable $iterator, callable $callback = self::SORT_CURRENT)
    {
        $iterator = $iterator instanceof \IteratorAggregate ? $iterator->getIterator() : $iterator;
        if (!$iterator instanceof \RecursiveIterator) {
            throw new \InvalidArgumentException('An instance of RecursiveIterator or IteratorAggregate creating it is required');
        }
        parent::__construct($iterator, $callback);
    }

    public function getSortedIterator($iterator)
    {
        $sortedIterator = new \RecursiveArrayIterator();
        $sorted = [];
        foreach ($iterator as $key => $value) {
            if ($iterator->hasChildren()) {
                $this->children[$key] = $iterator->getChildren();
            }
            $sorted[] = $this->generateElement($key, $value, $iterator);
        }

        usort($sorted, $this->realCallback);

        foreach ($sorted as $data) {
            $sortedIterator->append($data);
        }
        return $sortedIterator;
    }

    public function hasChildren()
    {
        return isset($this->children[$this->key()]);
    }

    public function getChildren()
    {
        return new self($this->children[$this->key()], $this->callback);
    }
}
