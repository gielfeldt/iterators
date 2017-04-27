<?php

namespace Gielfeldt\Iterators;

class StepIterator extends TraversableIterator
{
    private $step;

    public function __construct(\Traversable $iterator, int $step)
    {
        $this->step = $step;
        parent::__construct($iterator);
    }

    public function next()
    {
        $step = $this->step;
        while ($step-- > 0 && parent::valid()) {
            parent::next();
        }
    }
}
