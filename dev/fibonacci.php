<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\ShuffleIterator;
use Gielfeldt\Iterators\ValuesIterator;

$input = new \ArrayIterator(range(1, 10));
$fibonacci = new FiniteIterator(new \ArrayIterator([0, 1]), function ($iterator) {
    return $iterator->getInnerIterator()->valid();
    if ($iterator->getInnerIterator()->valid()) {
        print "NOT DONE!\n";
        return true;
    }
    print "DONE!\n";
    $iterator->getInnerIterator()->append(Iterator::sum($iterator->getInnerIterator()));
    if ($iterator->getInnerIterator()->current() < INF) {
        $iterator->getInnerIterator()->offsetUnset($iterator->getInnerIterator()->key() - 2);
        $iterator->getInnerIterator()->seek(1);
        return true;
    }
    return false;
}, FiniteIterator::REINDEX);

#print_r(iterator_to_array(new \LimitIterator($fibonacci, 4, 8)));

foreach ($fibonacci as $k => $v) {
    print "$k => $v\n";
    if ($k > 10) break;
}
exit;
