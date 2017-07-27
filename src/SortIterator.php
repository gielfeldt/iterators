<?php

namespace Gielfeldt\Iterators;

class SortIterator extends TraversableIterator implements \Countable
{
    const SORT_CURRENT = [__CLASS__, 'sortCurrent'];
    const SORT_KEY = [__CLASS__, 'sortKey'];
    const SORT_CURRENT_DESC = [__CLASS__, 'sortCurrentDesc'];
    const SORT_KEY_DESC = [__CLASS__, 'sortKeyDesc'];

    protected $callback;

    public function __construct(iterable $iterator, callable $callback = self::SORT_CURRENT)
    {
        $this->callback = \Closure::fromCallable($callback);
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

        usort($sorted, $this->callback);

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

    public static function sortCurrent($cmpA, $cmpB)
    {
        return $cmpA->current <=> $cmpB->current;
    }

    public static function sortKey($cmpA, $cmpB)
    {
        return $cmpA->key <=> $cmpB->key;
    }
}
