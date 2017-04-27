<?php

namespace Gielfeldt\Iterators;

class FlipIterator extends TraversableIterator
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
