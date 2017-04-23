<?php

namespace Gielfeldt\Iterators;

class CloningIterator extends IteratorIterator
{
    public function key()
    {
        $key = parent::key();
        return is_object($key) ? clone $key : $key;
    }

    public function current()
    {
        $current = parent::current();
        return is_object($current) ? clone $current : $current;
    }
}
