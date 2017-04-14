<?php

namespace Gielfeldt\Iterators;

class ReplaceableIterator implements \Iterator, \OuterIterator
{
    public function __construct(\Traversable $iterator = null)
    {
        $iterator = $iterator ?? new \EmptyIterator();
        $this->iterator = $iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator);
    }

    public function setInnerIterator(\Traversable $iterator)
    {
        $this->iterator = $iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator);
        $this->getInnerIterator()->rewind();
    }

    public function getInnerIterator()
    {
        return $this->iterator;
    }

    public function valid()
    {
        return $this->getInnerIterator()->valid();
    }

    public function key()
    {
        return $this->getInnerIterator()->key();
    }

    public function current()
    {
        return $this->getInnerIterator()->current();
    }

    public function rewind()
    {
        return $this->getInnerIterator()->rewind();
    }

    public function next()
    {
        return $this->getInnerIterator()->next();
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->getInnerIterator(), $method], $arguments);
    }
}
