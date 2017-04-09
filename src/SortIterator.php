<?php

namespace Gielfeldt\Iterators;

class SortIterator extends \SplHeap
{
    const SORT_CURRENT = [__CLASS__, 'sortCurrent'];
    const SORT_KEY = [__CLASS__, 'sortKey'];
    const SORT_SPL_FILE_INFO = [__CLASS__, 'sortSplFileInfo'];
    const SORT_ASC = 1;
    const SORT_DESC = 2;
    const SORT_REINDEX = 4;

    protected $iterator;
    protected $callback;
    protected $flags;
    protected $count = 0;
    protected $nullCurrent;

    public function __construct(\Traversable $iterator, callable $callback = self::SORT_CURRENT, int $direction = self::SORT_ASC, int $flags = 0)
    {
        $this->iterator = $iterator;
        $callback = \Closure::fromCallable($callback);
        $this->callback = $direction == self::SORT_DESC ? $callback : function ($a, $b) use ($callback) {
            return $callback($b, $a);
        };
        $this->flags = $flags;
    }

    public function rewind() {
        parent::rewind();
        while ($this->count()) {
            $this->extract();
        }
        foreach ($this->iterator as $key => $value) {
            $this->addElement($key, $value);
        }
        // When asking for "current" after iteration has ended, an iterator may
        // decide for itself what to return. We ask the iterator here what that
        // value is, so that we may return it ourselves after finished iteration.
        $this->nullCurrent = $this->iterator instanceof \Iterator ? $this->iterator->current() : null;
        $this->count = $this->count();
    }

    public function top() {
		if ($this->isEmpty()) {
			$this->rewind();
		}
		if ($this->isEmpty()) {
			return null;
		}
        return parent::top()->current;
	}

    protected function addElement($key, $value)
    {
        $this->insert((object) ['key' => $key, 'current' => $value]);
    }

    public function compare($a, $b)
    {
        return ($this->callback)($a, $b);
    }

    public function key()
    {
        // SplHeap keys are reversed.
        if ($this->flags & self::SORT_REINDEX) {
            return $this->count - parent::key() - 1;
        }
        return parent::current()->key ?? null;
    }

    public function current()
    {
        return parent::current()->current ?? $this->nullCurrent;
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->iterator, $method], $arguments);
    }

    static public function __callStatic($method, $arguments)
    {
        return call_user_func_array([get_class($this->iterator), $method], $arguments);
    }

    static public function sortCurrent($a, $b)
    {
        return $a->current <=> $b->current;
    }

    static public function sortKey($a, $b)
    {
        return $a->key <=> $b->key;
    }

    static public function sortSplFileInfo($a, $b)
    {
        return $a->current->getPathname() <=> $b->current->getPathname();
    }
}
