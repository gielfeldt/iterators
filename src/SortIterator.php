<?php

namespace Gielfeldt\Iterators;

class SortIterator extends IteratorIterator implements \Countable
{
    const SORT_CURRENT = [__CLASS__, 'sortCurrent'];
    const SORT_KEY = [__CLASS__, 'sortKey'];
    const SORT_SPL_FILE_INFO = [__CLASS__, 'sortSplFileInfo'];
    const SORT_ASC = 1;
    const SORT_DESC = 2;
    const SORT_REINDEX = 4;

    protected $direction;
    protected $flags;
    protected $callback;
    protected $realCallback;

    public function __construct(\Traversable $iterator, int $direction = self::SORT_ASC, int $flags = 0, callable $callback = self::SORT_CURRENT)
    {
        $this->direction = $direction;
        $this->flags = $flags;
        $this->callback = \Closure::fromCallable($callback);
        $this->realCallback = $direction == self::SORT_ASC ? $this->callback : function($cmpA, $cmpB) {
            return ($this->callback)($cmpB, $cmpA);
        };
        parent::__construct($this->getSortedIterator($iterator));
    }

    /**
     * @param \Traversable $iterator
     */
    public function getSortedIterator($iterator)
    {
        $sortedIterator = new \ArrayIterator();
        $sorted = [];
        foreach ($iterator as $key => $value) {
            $sorted[] = $this->generateElement($key, $value, $iterator);
        }

        usort($sorted, $this->realCallback);

        foreach ($sorted as $data) {
            $sortedIterator->append($data);
        }
        return $sortedIterator;
    }

    protected function generateElement($key, $value, $iterator)
    {
        return (object) ['key' => $key, 'current' => $value];
    }

    public function key()
    {
        if ($this->flags & self::SORT_REINDEX) {
            return $this->getInnerIterator()->key();
        }
        return $this->getInnerIterator()->current()->key ?? null;
    }

    public function current()
    {
        return $this->getInnerIterator()->current()->current ?? null;
    }

    public function count()
    {
        return $this->getInnerIterator()->count();
    }

    public function first()
    {
        $count = $this->getInnerIterator()->count();
        return $count ? $this->getInnerIterator()[0]->current : null;
    }

    public function last()
    {
        $count = $this->getInnerIterator()->count();
        return $count ? $this->getInnerIterator()[$count - 1]->current : null;
    }

    public function min()
    {
        return $this->direction == self::SORT_ASC ? $this->first() : $this->last();
    }

    public function max()
    {
        return $this->direction == self::SORT_ASC ? $this->last() : $this->first();
    }

    static public function sortCurrent($cmpA, $cmpB)
    {
        return $cmpA->current <=> $cmpB->current;
    }

    static public function sortKey($cmpA, $cmpB)
    {
        return $cmpA->key <=> $cmpB->key;
    }
}
