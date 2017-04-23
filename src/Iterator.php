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

    public static function nth(\Traversable $iterator, int $offset)
    {
        $iterator = new \IteratorIterator($iterator);
        $iterator->rewind();
        while ($iterator->valid() && $offset-- > 0) {
            $iterator->next();
        }
        return $iterator->current();
    }

    /**
     * @return string
     */
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

    public static function average(\Traversable $iterator)
    {
        return self::sum($iterator) / count(new CountableIterator($iterator));
    }

    public static function min(\Traversable $iterator)
    {
        return self::reduce($iterator, function ($carry, $value, $key) {
            return $carry < $value ? $carry : $value;
        }, INF);
    }

    public static function max(\Traversable $iterator)
    {
        return self::reduce($iterator, function ($carry, $value, $key) {
            return $carry > $value ? $carry : $value;
        }, -INF);
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

    public static function iterator_to_array_deep(\Traversable $iterator, $use_keys = true)
    {
        $result = [];
        foreach ($iterator as $key => $value) {
            $value = $value instanceof \Traversable ? self::iterator_to_array_deep($value, $use_keys) : $value;
            if ($use_keys) {
                $result[$key] = $value;
            }
            else {
                $result[] = $value;
            }
        }
        return $result;
    }
}
