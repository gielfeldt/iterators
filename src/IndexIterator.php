<?php

namespace Gielfeldt\Iterators;

class IndexIterator extends TraversableIterator implements \Countable
{
    private $indexingIterator;
    private $valid;

    public function __construct(\Traversable $iterator, iterable $indexes)
    {
        if ($indexes instanceof \Traversable) {
            $indexes = iterator_to_array(new \IteratorIterator($indexes), false);
        }
        sort($indexes);
        $this->indexingIterator = new \ArrayIterator($indexes);

        $iterator = $iterator instanceof \Countable ? $iterator : new CountableIterator($iterator, CountableIterator::CACHE_COUNT);
        parent::__construct($iterator);
    }

    public function count()
    {
        return min(count($this->getInnerIterator()), count($this->indexingIterator));
    }

    public function rewind()
    {
        parent::rewind();
        $this->indexingIterator->rewind();
        $this->next();
    }

    public function next()
    {
        $this->valid = $this->indexingIterator->valid();
        $index = $this->indexingIterator->current();
        while (parent::valid() && $this->getIndex() < $index) {
            parent::next();
        }
        $this->indexingIterator->next();
    }

    public function valid()
    {
        return $this->valid && parent::valid();
    }
}
