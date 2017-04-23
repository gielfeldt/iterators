<?php

namespace Gielfeldt\Iterators;

class ShuffleIterator extends IteratorIterator implements \Countable
{
    protected $min = INF;
    protected $max = -INF;

    public function __construct(\Traversable $iterator)
    {
        parent::__construct($this->getShuffledIterator($iterator));
    }

    /**
     * @param \Traversable $iterator
     */
    public function getShuffledIterator($iterator)
    {
        $sortedIterator = new \ArrayIterator();
        $sorted = [];
        foreach ($iterator as $key => $value) {
            $sorted[] = $this->generateElement($key, $value, $iterator);
            $this->min = $this->min < $value ? $this->min : $value;
            $this->max = $this->max > $value ? $this->max : $value;
        }

        shuffle($sorted);

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
        return $this->min;
    }

    public function max()
    {
        return $this->max;
    }
}
