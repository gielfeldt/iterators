<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\ShuffleIterator;
use Gielfeldt\Iterators\CombineIterator;
use Gielfeldt\Iterators\ValuesIterator;
use Gielfeldt\Iterators\KeysIterator;
use Gielfeldt\Iterators\RandomIterator;

$input = new \ArrayIterator(range(1, 40));
#$input = new CombineIterator(new \ArrayIterator(array_fill(0, 100, 0)), $input);

$d = new RandomIterator($input, 10);
#$d = new ValuesIterator($d);

foreach ($d as $k => $v) {
    print "$k => $v\n";
}
exit;
