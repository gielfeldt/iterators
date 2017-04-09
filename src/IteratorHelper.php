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
}
