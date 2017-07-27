<?php

namespace Gielfeldt\Iterators;

class AppendIterator extends \AppendIterator
{
    public function __construct(iterable $iterators)
    {
        parent::__construct();
        foreach ($iterators as $iterator) {
            if (!is_iterable($iterator)) {
                throw new \InvalidArgumentException('Arguments must be of type iterable!');
            }
            $this->append($iterator instanceof \Iterator ? $iterator : (function ($i) { yield from $i; })($iterator));
        }
    }
}
