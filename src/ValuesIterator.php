<?php

namespace Gielfeldt\Iterators;

class ValuesIterator extends IteratorIterator
{
    public function key()
    {
        return $this->getIndex();
    }
}
