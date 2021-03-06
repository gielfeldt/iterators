<?php

namespace Gielfeldt\Iterators;

class UniqueIterator extends \FilterIterator
{
    protected $found;
    protected $callback;
    protected $method;

    const ASSUME_SORTED = 1;
    const REINDEX = 2;
    const UNIQUE_CURRENT = [__CLASS__, 'filterCurrent'];
    const UNIQUE_KEY = [__CLASS__, 'filterKey'];

    public function __construct(\Traversable $iterator, int $flags = 0, callable $callback = self::UNIQUE_CURRENT)
    {
        parent::__construct($iterator);
        $this->callback = \Closure::fromCallable($callback);

        if ($flags & self::ASSUME_SORTED) {
            $this->method = \Closure::fromCallable([$this, 'filterAssumeSorted']);
        } else {
            $this->method = \Closure::fromCallable([$this, 'filterAssumeUnsorted']);
        }
    }

    public function rewind()
    {
        $this->found = [];
        return parent::rewind();
    }

    public function filterAssumeUnsorted($key)
    {
        if (isset($this->found[$key])) {
            return false;
        }
        $this->found[$key] = true;
        return true;
    }

    public function filterAssumeSorted($key)
    {
        if (isset($this->found[$key])) {
            return false;
        }
        $this->found = [$key => true];
        return true;
    }

    public static function filterCurrent($iterator)
    {
        return $iterator->current();
    }

    public static function filterKey($iterator)
    {
        return $iterator->key();
    }

    public function accept()
    {
        $key = ($this->callback)($this->getInnerIterator());
        $key = is_scalar($key) ? $key : (is_object($key) ? spl_object_hash($key) : serialize($key));
        return ($this->method)($key);
    }
}
