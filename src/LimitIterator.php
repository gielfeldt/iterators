<?php

namespace Gielfeldt\Iterators;

class LimitIterator extends \LimitIterator implements \Countable
{
    private $count;

    public function __construct(\Traversable $iterator, int $offset = 0, int $count = -1)
    {
        parent::__construct(new CountableIterator($iterator, CountableIterator::CACHE_COUNT), $offset, $count);
        $this->count = $count < 0 || $count < $this->getInnerIterator()->count() ? $this->getInnerIterator()->count() : $count;
    }

    public function count()
    {
        return $this->count;
    }
}
