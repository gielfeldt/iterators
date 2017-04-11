<?php

namespace Gielfeldt\Iterators;

class RecursiveSortIterator extends SortIterator implements \RecursiveIterator
{
    protected $children = [];

    public function __construct(\Traversable $iterator, int $direction = self::SORT_ASC, int $flags = 0, callable $callback = self::SORT_CURRENT)
    {
        $iterator = $iterator instanceof \Iterator ? $iterator : $iterator->getIterator();
        if (!$iterator instanceof \RecursiveIterator) {
            throw new \InvalidArgumentException('An instance of RecursiveIterator or IteratorAggregate creating it is required');
        }
        parent::__construct($iterator, $direction, $flags, $callback);
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
        return new self($this->children[$this->key()], $this->direction, $this->flags, $this->callback);
    }
}
