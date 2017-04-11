<?php

namespace Gielfeldt\Iterators;

class IteratorHelper
{
    public static function instanceOf($iterator, $class)
    {
        if ($iterator instanceof $class) {
            return true;
        }
        if ($iterator instanceof \OuterIterator) {
            return self::instanceOf($iterator->getInnerIterator(), $class);
        }
        return false;
    }

    public static function getInnerIterators(\Traversable $iterator)
    {
        if ($iterator instanceof \OuterIterator) {
            return array_merge([$iterator], self::getInnerIterators($iterator->getInnerIterator()));
        }
        return [];
    }

    public static function getInnermostIterator(\Traversable $iterator)
    {
        $iterators = self::getInnerIterators($iterator);
        return $iterators ? end($iterators) : null;
    }
}
