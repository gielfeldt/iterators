<?php

namespace Gielfeldt\Iterators;

class ShuffleIterator extends ReplaceableIterator implements \Countable
{
    private $min = INF;
    private $max = -INF;
    private $innerIterator;

    public function __construct(\Traversable $iterator)
    {
        $this->innerIterator = $iterator;
        parent::__construct(new \ArrayIterator());
    }

    public function rewind()
    {
        $shuffledIterator = new \ArrayIterator();
        $items = [];
        foreach ($this->innerIterator as $key => $value) {
            $items[] = $this->generateElement($key, $value, $this->innerIterator);
            $this->min = $this->min < $value ? $this->min : $value;
            $this->max = $this->max > $value ? $this->max : $value;
        }

        shuffle($items);

        foreach ($items as $data) {
            $shuffledIterator[$data->key] = $data->current;
        }
        $this->setInnerIterator($shuffledIterator);
    }

    /**
     * @param \Traversable $iterator
     */
    protected function generateElement($key, $value, $iterator)
    {
        return (object) ['key' => $key, 'current' => $value];
    }

    public function count()
    {
        return $this->getInnerIterator()->count();
    }

    public function first()
    {
        $count = $this->getInnerIterator()->count();
        return $count ? $this->getInnerIterator()[0] : null;
    }

    public function last()
    {
        $count = $this->getInnerIterator()->count();
        return $count ? $this->getInnerIterator()[$count - 1] : null;
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

class ShuffleIterator2 implements \IteratorAggregate
{
    private $innerIterator;

    public function __construct(\Traversable $iterator, $persist = false)
    {
        $this->innerIterator = $iterator;
        $this->persist = $persist;
    }

    public function getIterator()
    {
        #$shuffledIterator = new class() extends \ArrayIterator {
        #    public function 
        #}
        $shuffledIterator = new \ArrayIterator();
        $count = 0;
        foreach ($this->innerIterator as $key => $value) {
            $index = rand(0, $count);
            if ($index == $count) {
                #print "a";
                $shuffledIterator[] = [$key, $value];
            }
            else {
                #print "b";
                $shuffledIterator[] = $shuffledIterator[$index];
                $shuffledIterator[$index] = [$key, $value];
            }
            $count++;
        }
        #return $shuffledIterator;
        return new \Gielfeldt\Iterators\IndexedIterator($shuffledIterator);
        return new class($shuffledIterator) extends \IteratorIterator {
            public function key() { return parent::current()[0]; }
            public function current() { return parent::current()[1]; }
        };
    }
}

class IndexedIterator extends \IteratorIterator {
    public function key() { return parent::current()[0]; }
    public function current() { return parent::current()[1]; }
}
