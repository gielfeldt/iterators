<?php

namespace Gielfeldt\Iterators;

class CountableIterator extends IteratorIterator implements \Countable
{
    const CACHE_COUNT = 1;

    protected $count;
    protected $flags;

    public function __construct(\Traversable $iterator, $flags = self::CACHE_COUNT)
    {
        $this->flags = $flags;
        parent::__construct($iterator);
    }

    public function count()
    {
        if (!($this->flags & self::CACHE_COUNT) || !isset($this->count)) {
            if ($this->getInnerIterator() instanceof \Countable) {
                $this->count = intval($this->getInnerIterator()->count());
            }
            else {
                $this->count = 0;
                $this->rewind();
                while ($this->valid()) {
                    $this->count++;
                    $this->next();
                }
            }
        }
        return $this->count;
    }
}
