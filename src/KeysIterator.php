<?php

namespace Gielfeldt\Iterators;

class KeysIterator extends TraversableIterator
{
    public function key()
    {
        return $this->getIndex();
    }

    public function current()
    {
        return parent::key();
    }
}
