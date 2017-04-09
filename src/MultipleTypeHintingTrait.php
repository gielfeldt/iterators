<?php

namespace Gielfeldt\Iterators;

trait MultipleTypeHintingTrait
{
    public function checkTypeHinting($variable, string ...$types)
    {
        $found = 0;
        foreach ($types as $type) {
            switch ($type) {
                case 'int': $found += is_integer($variable) ? 1 : 0; break;
                case 'string': $found += is_string($variable) ? 1 : 0; break;
                case 'float': $found += is_float($variable) ? 1 : 0; break;
                case 'scalar': $found += is_scalar($variable) ? 1 : 0; break;
                case 'object': $found += is_object($variable) ? 1 : 0; break;
                case 'array': $found += is_array($variable) ? 1 : 0; break;
                case 'callable': $found += is_callable($variable) ? 1 : 0; break;
                case 'iterable': $found += is_array($variable) || $variable instanceof \Traversable ? 1 : 0; break;
                case 'recursive_iterable': $found += $variable instanceof \RecursiveIterator || ($variable instanceof \IteratorAggregate && $variable->getIterator() instanceof \RecursiveIterator) ? 1 : 0; break;
                case 'countable': $found += is_array($variable) || $variable instanceof \Countable ? 1 : 0; break;
                default:
                    $found += $variable instanceof $type ? 1 : 0; break;
            }
        }
        if (!$found) {
            throw new \InvalidArgumentException('Argument received of type: ' . gettype($variable) . '. Expected: ' . implode(', ', $types));
        }
    }

}
