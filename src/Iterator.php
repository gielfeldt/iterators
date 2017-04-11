<?php

namespace Gielfeldt\Iterators;

class Iterator
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

    public static function getInnerIterators(\Traversable $iterator, $include_self = false)
    {
        $result = $include_self ? [$iterator] : [];
        if ($iterator instanceof \OuterIterator) {
            return array_merge($result, self::getInnerIterators($iterator->getInnerIterator(), true));
        }
        return $result;
    }

    public static function getInnermostIterator(\Traversable $iterator)
    {
        $iterators = self::getInnerIterators($iterator, true);
        return $iterators ? end($iterators) : false;
    }

    public static function reduce(\Traversable $iterator, callable $callback, $initial = null)
    {
        $callback = \Closure::fromCallable($callback);
        iterator_apply($iterator, function ($iterator) use (&$initial, $callback) {
            $initial = $callback($initial, $iterator->current(), $iterator->key());
            return true;
        }, [$iterator]);
        return $initial;
    }

    public static function sum(\Traversable $iterator)
    {
        return self::reduce($iterator, function ($carry, $value, $key) {
            return $carry + $value;
        }, 0);
    }

    public static function product(\Traversable $iterator)
    {
        return self::reduce($iterator, function ($carry, $value, $key) {
            return $carry * $value;
        }, 1);
    }

    public static function concatenate(\Traversable $iterator)
    {
        return self::reduce($iterator, function ($carry, $value, $key) {
            return $carry . $value;
        }, '');
    }

    public static function implode($separator, \Traversable $iterator)
    {
        $result = self::reduce($iterator, function ($carry, $value, $key) use ($separator) {
            return $carry . $value . $separator;
        }, '');
        $result = mb_substr($result, 0, -mb_strlen($separator));
        return $result;
    }

    public static function chunk(\Traversable $iterator, $size)
    {
        if (!$iterator instanceof \Countable) {
            $iterator = new CountableIterator($iterator);
        }
        $chunked = new \ArrayIterator();
        $offset = 0;
        $count = count($iterator);
        while ($count > 0) {
            $chunked->append(new \LimitIterator($iterator, $offset, $size));
            $offset += $size;
            $count -= $size;
        }
        return $chunked;
    }
}
