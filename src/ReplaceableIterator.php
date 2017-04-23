<?php

namespace Gielfeldt\Iterators;

class ReplaceableIterator implements \Iterator, \OuterIterator
{
    private $index;

    public function __construct(\Traversable $iterator = null)
    {
        $iterator = $iterator ?? new \EmptyIterator();
        $this->setInnerIterator($iterator);
    }

    public function setInnerIterator(\Traversable $iterator)
    {
        $this->iterator = $iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator);
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
        $this->index = 0;
        return $this->getInnerIterator()->rewind();
    }

    public function next()
    {
        $this->index++;
        return $this->getInnerIterator()->next();
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->getInnerIterator(), $method], $arguments);
    }
}
