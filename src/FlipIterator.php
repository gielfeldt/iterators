<?php

namespace Gielfeldt\Iterators;

class FlipIterator extends IteratorIterator
{
    public function key()
    {
        return parent::current();
    }

    public function current()
    {
        return parent::key();
    }
}
