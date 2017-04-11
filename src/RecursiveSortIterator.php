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

        // When asking for "current" after iteration has ended, an iterator may
        // decide for itself what to return. We ask the iterator here what that
        // value is, so that we may return it ourselves after finished iteration.
        $this->nullCurrent = $iterator instanceof \Iterator ? $iterator->current() : null;

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
