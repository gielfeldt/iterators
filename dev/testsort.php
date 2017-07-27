<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\SortIterator;

$input = [];
for ($i = 0; $i < 10; $i++) {
    $input[] = rand(1, 1000);
}

var_export($input);

$i = new ArrayIterator($input);
$s = new SortIterator($i);

var_export(iterator_to_array($s));
