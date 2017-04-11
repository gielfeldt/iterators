<?php

namespace Gielfeldt\Iterators;

class IntersectIterator extends DiffIterator
{
    public function accept() {
        $found = 0;
        foreach ($this->iterators as $iterator) {
            foreach ($iterator as $key => $value) {
                if (($this->callback)($this->getInnerIterator(), $key, $value)) {
                    $found++;
                    continue;
                }
            }
        }
        return $found == count($this->iterators);
	}
}
