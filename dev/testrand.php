<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\Iterator;
use Gielfeldt\Iterators\RandomIterator;
use Gielfeldt\Iterators\ReservoirSamplingIterator;
use Gielfeldt\Iterators\RepeatIterator;
use Gielfeldt\Iterators\DebugIterator;

$input = range(1, 20);
#shuffle($input);
$i = new ArrayIterator($input);
foreach ($i as $k => $v) {
    print "$k => $v\n";
}

print "RANDOM!\n";
/*
$r = new RandomIterator($i, 5);
foreach ($r as $k => $v) {
    print "$k => $v\n";
}
exit;
foreach ($r as $k => $v) {
    print "$k => $v\n";
}
/**/
/**/
$r = new ReservoirSamplingIterator($i, 5);
foreach ($r as $k => $v) {
    print "$k => $v\n";
}
#foreach ($r as $k => $v) {
#    print "$k => $v\n";
#}
/**/
