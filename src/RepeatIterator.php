<?php

namespace Gielfeldt\Iterators;

class RepeatIterator extends \IteratorIterator
{
    protected $count;
    protected $currentIteration = 0;

    public function __construct(\Traversable $iterator, int $count)
    {
        if ($count < 0) {
            throw new \InvalidArgumentException('Count cannot be less than 0.');
        }
        parent::__construct($iterator);
        $this->count = $count;
    }

    public function rewind()
    {
        $this->currentIteration = 0;
        return parent::rewind();
    }

    public function valid()
    {
        if (!$this->count) {
            return false;
        }
        $valid = parent::valid();
        if (!$valid) {
            $this->currentIteration++;
            if ($this->currentIteration < $this->count) {
                $valid = true;
                parent::rewind();
            }
        }
        return $valid;
    }
}
