<?php

namespace Gielfeldt\Iterators;

class RepeatIterator extends IteratorIterator implements \Countable
{
    protected $count;
    protected $currentIteration = 0;
    protected $innerCount;

    public function __construct(\Traversable $iterator, int $count)
    {
        if ($count < 0) {
            throw new \InvalidArgumentException('Count cannot be less than 0.');
        }

        $this->count = $count;
        parent::__construct($iterator);
    }

    public function count()
    {
        if (!isset($this->innerCount)) {
            $this->innerCount = count(new CountableIterator($this->getInnerIterator()));
        }
        return $this->innerCount * $this->count;
    }

    public function getIterationCount()
    {
        return $this->count;
    }

    public function getCurrentIteration()
    {
        return $this->currentIteration;
    }

    public function rewind()
    {
        $this->currentIteration = 0;
        return parent::rewind();
    }

    public function next()
    {
        parent::next();
        if (!parent::valid()) {
            $this->currentIteration++;
            parent::rewind();
        }
    }

    public function valid()
    {
        return $this->currentIteration < $this->count && parent::valid();
    }
}
