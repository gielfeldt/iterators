<?php

namespace Gielfeldt\Iterators;

class InterleaveIterator implements \Iterator
{
    protected $realIterators;
    protected $iterators;

    public function __construct(\Traversable ...$iterators)
    {
        $this->realIterators = new \ArrayIterator($iterators);
        foreach ($iterators as $iterator) {
            $this->appendIterator($iterator);
        }
        $this->iterators = new \InfiniteIterator($this->realIterators);
    }

    public function appendIterator(\Traversable $iterator)
    {
        $this->realIterators->append($iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator));
    }

    public function rewind()
    {
        foreach ($this->realIterators as $iterator) {
            $iterator->rewind();
        }
        $this->iterators->rewind();
    }

    public function valid()
    {
        $count = count($this->realIterators);
        while ($count-- >= 0) {
            if ($this->iterators->current()->valid()) {
                return true;
            }
            $this->next();
        }
        return false;
    }

    public function key()
    {
        return $this->iterators->current()->key();
    }

    public function current()
    {
        return $this->iterators->current()->current();
    }

    public function next()
    {
        if ($this->iterators->current()->valid()) {
            $this->iterators->current()->next();
        }
        $this->iterators->next();
    }
}
