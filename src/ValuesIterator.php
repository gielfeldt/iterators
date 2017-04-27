<?php

namespace Gielfeldt\Iterators;

class ValuesIterator extends TraversableIterator
{
    public function key()
    {
        return $this->getIndex();
    }
}
