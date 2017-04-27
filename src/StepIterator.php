<?php

namespace Gielfeldt\Iterators;

class StepIterator extends TraversableIterator
{
    private $step;
    private $offset;

    public function __construct(\Traversable $iterator, int $step, int $offset = 0)
    {
        $this->step = $step;
        $this->offset = $offset;
        parent::__construct($iterator);
    }

    public function rewind()
    {
        parent::rewind();
        $offset = $this->offset;
        while ($offset-- > 0 && parent::valid()) {
            parent::next();
        }
    }

    public function next()
    {
        $step = $this->step;
        while ($step-- > 0 && parent::valid()) {
            parent::next();
        }
    }
}
