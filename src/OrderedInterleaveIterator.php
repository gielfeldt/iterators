<?php

namespace Gielfeldt\Iterators;

class OrderedInterleaveIterator extends \SplHeap
{
    const DEFAULT_COMPARE = [__CLASS__, 'defaultCompare'];

    protected $iterators;
    protected $compareFunction = self::DEFAULT_COMPARE;


    public function __construct(\Traversable ...$iterators)
    {
        $this->iterators = new \ArrayIterator();
        foreach ($iterators as $iterator) {
            $this->add($iterator);
        }
    }

    public function setCompare($compareFunction)
    {
        $this->compareFunction = $compareFunction;
        return $this;
    }

    public function compare($a, $b)
    {
        return call_user_func_array($this->compareFunction, [$b[2], $a[2]]);
    }

    public function defaultCompare($a, $b)
    {
        return $a <=> $b;
    }

    public function add(\Traversable $iterator)
    {
        $this->iterators->append($iterator instanceof \Iterator ? $iterator : new \IteratorIterator($iterator));
        return $this;
    }

    public function rewind()
    {
        while (!$this->isEmpty()) {
            $this->extract();
        }
        foreach ($this->iterators as $no => $iterator) {
            $iterator->rewind();
            if ($iterator->valid()) {
                $this->insert([$no, $iterator->key(), $iterator->current()]);
                $iterator->next();
            }
        }
        $this->idx = 0;
    }

    public function key()
    {
        return $this->top()[1];
    }

    public function current()
    {
        return $this->top()[2];
    }

    public function next()
    {
        $this->idx++;
        $used = $this->extract();
        $no = $used[0];
        if ($this->iterators[$no]->valid()) {
            $this->insert([$no, $this->iterators[$no]->key(), $this->iterators[$no]->current()]);
            $this->iterators[$no]->next();
        }
    }
}
