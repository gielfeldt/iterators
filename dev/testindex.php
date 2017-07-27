<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\StepIterator;
use Gielfeldt\Iterators\IndexIterator;
use Gielfeldt\Iterators\DebugIterator;

$input = range(1, 20);
$i = new ArrayIterator($input);
foreach ($i as $k => $v) {
    print "$k => $v\n";
}

$indexes = new ArrayIterator([1, 4, 7]);

#$r = new IndexIterator($i, $indexes);
$r = new StepIterator($i, 3, 3);
foreach ($r as $k => $v) {
    print "$k => $v\n";
}
