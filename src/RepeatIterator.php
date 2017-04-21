<?php

namespace Gielfeldt\Iterators;

class RepeatIterator extends \IteratorIterator implements \Countable
{
    protected $count;
    protected $currentIteration = 0;
    protected $fraction;
    protected $innerCount;
    protected $innerLimit;
    protected $innerPos = 0;

    public function __construct(\Traversable $iterator, float $count)
    {
        if ($count < 0) {
            throw new \InvalidArgumentException('Count cannot be less than 0.');
        }
        $this->fraction = fmod($count, 1);

        // If count is less than 1, we need to know the size of the iterator
        // beforehand, in order to know when to quit.
        if ($count > 0 && $count < 1) {
            $this->innerCount = count(new CountableIterator($iterator));
            $this->innerLimit = round($this->innerCount * $this->fraction);
            $count = 1;
        }

        parent::__construct($iterator);
        $this->count = ceil($count);
    }

    public function count()
    {
        if ($this->count == INF) {
            return $this->count;
        }

        if (!isset($this->innerCount)) {
            $this->innerCount = count(new CountableIterator($this->getInnerIterator()));
            $this->innerLimit = round($this->innerCount * $this->fraction);
        }
        $count = $this->innerCount * $this->count;
        if ($this->fraction > 0) {
            $count -= $this->innerCount - $this->innerLimit;
        }
        return $count;
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
        $this->innerPos = 0;
        return parent::rewind();
    }

    public function next()
    {
        $this->innerPos++;
        return parent::next();
    }

    public function valid()
    {
        if ($this->count <= 0) {
            return false;
        }

        $valid = parent::valid();
        if (!$valid) {
            $this->currentIteration++;
            if ($this->currentIteration < $this->count) {
                if (!isset($this->innerLimit) && $this->fraction > 0) {
                    $this->innerCount = $this->innerPos;
                    $this->innerLimit = round($this->innerCount * $this->fraction);
                }
                $this->innerPos = 0;
                parent::rewind();
                return $this->valid();
            }
        }
        elseif (
            isset($this->innerLimit) && $this->innerPos >= $this->innerLimit
            && $this->currentIteration == $this->count - 1
        ) {
            $valid = false;
        }
        return $valid;
    }
}
