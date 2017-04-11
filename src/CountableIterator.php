<?php

namespace Gielfeldt\Iterators;

class CountableIterator extends \IteratorIterator implements \Countable
{
    public function __construct(\Traversable $iterator)
    {
        parent::__construct($iterator);
    }

    public function count()
    {
        if ($this->getInnerIterator() instanceof \Countable) {
            return $this->getInnerIterator()->count();
        }
        $count = 0;
        $this->rewind();
        while ($this->valid()) {
            $count++;
            $this->next();
        }
        return $count;
    }
}
