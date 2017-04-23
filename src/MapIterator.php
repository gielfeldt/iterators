<?php

namespace Gielfeldt\Iterators;

class MapIterator extends IteratorIterator
{
    protected $currentKey;
    protected $currentValue;
    protected $currentIdx;

    public function __construct(\Traversable $iterator, callable $callback)
    {
        parent::__construct($iterator);
        $this->callback = \Closure::fromCallable($callback);
    }

    private function map()
    {
        if ($this->valid()) {
            $result = ($this->callback)($this->getInnerIterator());
            if (is_array($result)) {
                list($this->currentKey, $this->currentValue) = $result;
                if (is_numeric($this->currentKey) && intval($this->currentKey) >= $this->idx) {
                    $this->idx = intval($this->currentKey) + 1;
                }
            } else {
                $this->currentKey = $this->idx++;
                $this->currentValue = $result;
            }

        } else {
            $this->currentKey = null;
            $this->currentValue = null;
        }
    }

    public function rewind()
    {
        parent::rewind();
        $this->idx = 0;
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
