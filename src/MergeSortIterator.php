<?php

namespace Gielfeldt\Iterators;

class MergeSortIterator implements \Iterator
{
    const SORT_CURRENT = [__CLASS__, 'sortCurrent'];
    const SORT_KEY = [__CLASS__, 'sortKey'];
    const SORT_CURRENT_DESC = [__CLASS__, 'sortCurrentDesc'];
    const SORT_KEY_DESC = [__CLASS__, 'sortKeyDesc'];

    protected $iterators;
    protected $current;
    protected $key;

    public function __construct(iterable $iterators, callable $callback = self::SORT_CURRENT)
    {
        $this->iterators = new MapIterator(Iterator::iterableToIterator($iterators), function ($iterator) {
            return Iterator::iterableToIterator($iterator->current());
        });
        $this->currentSet = new class($callback) extends \SplHeap {
            protected $callback;
            public function __construct(callable $callback) {
                $this->callback = \Closure::fromCallable($callback);
            }
            public function compare($a, $b) {
                return ($this->callback)($b, $a);
            }
        };
    }

    public function rewind()
    {
        foreach ($this->iterators as $iterator) {
            $iterator->rewind();
            $this->collect($iterator);
        }
        $this->next();
    }

    public function valid()
    {
        return $this->valid;
    }

    public function collect($iterator)
    {
        if ($iterator->valid()) {
            $object = (object) [
                'key' => $iterator->key(),
                'current' => $iterator->current(),
                'iterator' => $iterator
            ];
            $this->currentSet->insert($object);
        }        
    }

    public function next()
    {
        if ($this->valid = !$this->currentSet->isEmpty()) {
            $object = $this->currentSet->extract();
            $this->current = $object;
            $object->iterator->next();
            $this->collect($object->iterator);
        }
        else {
            $this->current = null;
        }
    }

    public function key()
    {
        return $this->current->key;
    }

    public function current()
    {
        return $this->current->current;
    }

    public static function sortCurrent($cmpA, $cmpB)
    {
        return $cmpA->current <=> $cmpB->current;
    }

    public static function sortKey($cmpA, $cmpB)
    {
        return $cmpA->key <=> $cmpB->key;
    }

    public static function sortCurrentDesc($cmpA, $cmpB)
    {
        return $cmpB->current <=> $cmpA->current;
    }

    public static function sortKeyDesc($cmpA, $cmpB)
    {
        return $cmpB->key <=> $cmpA->key;
    }

}
