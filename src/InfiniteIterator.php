<?php

namespace Gielfeldt\Iterators;

class InfiniteIterator extends \InfiniteIterator
{
    const REINDEX = 1;

    protected $index = 0;
    protected $flags = 0;

    public function __construct(\Traversable $iterator, $flags = 0)
    {
        $this->flags = $flags;
        $iterator = $iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator);
        parent::__construct($iterator);
    }

    public function rewind()
    {
        $this->index = 0;
        return parent::rewind();
    }

    public function next()
    {
        $this->index++;
        return parent::next();
    }

    public function key()
    {
        return $this->flags & self::REINDEX ? $this->index : parent::key();
    }
}
