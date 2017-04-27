<?php

namespace Gielfeldt\Iterators;

class RandomIterator implements \IteratorAggregate, \Countable
{
    private $count;
    private $innerIterator;

    public function __construct(\Traversable $iterator, int $count)
    {
        $this->innerIterator = new CountableIterator($iterator, CountableIterator::CACHE_COUNT);
        $this->count = $count;
    }

    public function getIterator()
    {
        $innerCount = $this->innerIterator->count();
        $count = min($this->count, $innerCount);
        $indexes = [];
        for ($i = 0; $i < $count; $i++) {
            do {
                $index = rand(0, $innerCount - 1);
            } while (isset($indexes[$index]));
            $indexes[$index] = $index;
        }
        return new IndexIterator($this->innerIterator, $indexes);
    }

    public function count()
    {
        return min(count($this->innerIterator), $this->count);
    }
}
