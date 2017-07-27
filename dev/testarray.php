<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\ArrayIterator;
use Gielfeldt\Iterators\ShuffleIterator;

foreach (range(10, 20) as $i) $keys[] = "key:$i";
foreach (range(20, 30) as $i) $values[] = "value:$i";

$input = array_combine($keys, $values);
$iterator = new ArrayIterator($input);

foreach ($iterator as $key => $value) {
    dump($iterator);
}

function dump($i) {
    // print $i->getIndex() . ': ' . $i->key() . ' => ' . $i->current() . "\n";
    print $i->key() . ' => ' . $i->current() . "\n";
}

print_r(iterator_to_array($iterator));

var_dump($iterator['key:11']);
#$iterator[40] = 'hello';
$iterator[null] = 'hello';

print_r(iterator_to_array($iterator));
$iterator->seek(12);
dump($iterator);