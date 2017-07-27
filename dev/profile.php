<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\SortIterator;
use Gielfeldt\Iterators\SortIteratorOld;
use JBZoo\Profiler\Benchmark;


$input = [];
for ($i = 0; $i < 10000; $i++) {
    $input[] = rand(1, 1000);
}

// Compare performance of functions
Benchmark::compare([
    'sort'   => function () use ($input) {
        $iterator = new SortIterator(new \ArrayIterator($input));
    },
    'sortold'  => function () use ($input) {
        $iterator = new SortIteratorOld(new \ArrayIterator($input));
    },
], array('count' => 10, 'name' => 'Sort'));
