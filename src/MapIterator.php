<?php

namespace Gielfeldt\Iterators;

class MapIterator extends \IteratorIterator
{
    protected $currentKey;
    protected $currentValue;

    public function __construct(\Traversable $iterator, callable $callback)
    {
        parent::__construct($iterator);
        $this->callback = \Closure::fromCallable($callback);
    }

    private function map()
    {
        if ($this->valid()) {
            list($this->currentKey, $this->currentValue) = ($this->callback)($this->getInnerIterator());
        }
        else {
            $this->currentKey = null;
            $this->currentValue = null;
        }
    }

    public function rewind()
    {
        parent::rewind();
        $this->map();
    }

    public function next()
    {
        parent::next();
        $this->map();
    }

    public function key()
    {
        return $this->currentKey;
    }

    public function current()
    {
        return $this->currentValue;
    }
}
