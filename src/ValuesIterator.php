<?php

namespace Gielfeldt\Iterators;

class ValuesIterator extends \IteratorIterator
{
    protected $idx;

    public function rewind()
    {
        parent::rewind();
        $this->idx = 0;
    }

    public function next()
    {
        parent::next();
        $this->idx++;
    }

    public function key()
    {
        return $this->idx;
    }
}
