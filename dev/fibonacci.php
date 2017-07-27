<?php

require __DIR__ . '/../vendor/autoload.php';

use Gielfeldt\Iterators\Iterator;
use Gielfeldt\Iterators\InfiniteIterator;
use Gielfeldt\Iterators\ValuesIterator;

$fibonacciGen = function ($a, $b) {
    yield $a;
    yield $b;
    do {
        $result = $a + $b;
        $a = $b;
        $b = $result;
        yield $result;
    } while ($result < INF);
};

#print_r(iterator_to_array(new \LimitIterator($fibonacciGen(2, 1), 0, 10), false));
$gen = new \CachingIterator(new \NoRewindIterator($fibonacciGen(0, 1)), \CachingIterator::FULL_CACHE);
var_dump(Iterator::nth($gen, 0));
var_dump(Iterator::nth($gen, 1));
var_dump(Iterator::nth($gen, 2));
var_dump(Iterator::nth($gen, 3));
var_dump(Iterator::nth($gen, 4));
var_dump(Iterator::nth($gen, 5));
var_dump(Iterator::nth($gen, 6));
exit;

$input = new \ArrayIterator(range(1, 10));
$array = new \ArrayIterator([0, 1]);
$infin = new InfiniteIterator($array, function ($iterator) {
    print $iterator->getCurrentIteration();
    return true;
});

$fibonacci = new ValuesIterator($infin);
$fibonacci->rewind();
#$fibonacci->seek(1);
$fibonacci->next();
var_dump($fibonacci->current());
exit;

#print_r(iterator_to_array(new \LimitIterator($fibonacci, 4, 8)));

while ($fibonacci->valid()) {
#foreach ($fibonacci as $k => $v) {
    list ($k, $v) = [$fibonacci->key(), $fibonacci->current()];
    $fibonacci->next();
    print "$k => $v\n";
    if ($k > 10) break;
}
exit;
