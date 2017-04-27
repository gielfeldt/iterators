<?php

namespace Gielfeldt\Iterators;

class CombineIterator extends TraversableIterator
{
    const NEED_ANY = \MultipleIterator::MIT_NEED_ANY;
    const NEED_ALL = \MultipleIterator::MIT_NEED_ALL;

    public function __construct(\Traversable $keysIterator, \Traversable $valuesIterator, int $flags = self::NEED_ANY)
    {
        $iterator = new \MultipleIterator($flags | \MultipleIterator::MIT_KEYS_ASSOC);
        $iterator->attachIterator($keysIterator, 'key');
        $iterator->attachIterator($valuesIterator, 'value');
        parent::__construct($iterator);
    }

    public function key()
    {
        $current = parent::current();
        return $current['key'] ?? null;
    }

    public function current()
    {
        $current = parent::current();
        return $current['value'] ?? null;
    }
}
