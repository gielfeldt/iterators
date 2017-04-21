<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\Iterator;
use Gielfeldt\Iterators\ReplaceableIterator;
use Gielfeldt\Iterators\EventIterator;

$a = new \ArrayIterator(range(1, 20));

$e = new EventIterator($a);

$e->attach('rewind', function ($iterator) {
    print "Rewinding\n";
});

$e->attach('unfinished', function ($iterator) {
    print "Unfinished\n";
});

$e->attach('finished', function ($iterator) {
    print "Finished\n";
});

$e->attach('next', function ($iterator) {
    print "Next\n";
});

foreach ($e as $k => $v) {
    print "$k => $v\n";
}
