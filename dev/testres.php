<?php

require './vendor/autoload.php';

use Gielfeldt\Iterators\ReservoirSamplingIterator;
use Gielfeldt\Iterators\RandomIterator;

$size = 40;
$samples = 15;

$a = range(1, $size);
$i = new \ArrayIterator($a);

$r1 = new RandomIterator($i, $samples);
$r2 = new ReservoirSamplingIterator($i, $samples);

$stats = array_fill(1, $size, 0);
dump_stats($stats, true);
for ($u = 0; $u < 100000; $u++) {
    foreach ($r2 as $v) {
        $stats[$v]++;
    }
    dump_stats($stats);
}

function dump_stats($stats, $first = false) {
    if (!$first) {
        $size = count($stats);
        echo "\033[{$size}A";
    }
    $total = array_sum($stats);
    $total = $total > 0 ? $total : 1;
    foreach ($stats as $k => $v) {
        printf("%2s : %10s : %.4f%%    \n", $k, $v, $v / $total * 100);
    }
}
