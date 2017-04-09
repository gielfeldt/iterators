<?php

namespace Gielfeldt\Iterators;

class FieldedIterator extends \IteratorIterator
{
    const AUTODETECT_FIELDS = 1;

    protected $fields;

    public function __construct(\Traversable $iterator, array $fields = [], int $flags = 0)
    {
        $this->fields = $fields;
    }

    public function current()
    {
        $current = parent::current();
        return $current;
    }
}
