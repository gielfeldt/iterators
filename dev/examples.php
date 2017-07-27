<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\StepIterator;

$input = new \ArrayIterator(range(1, 10));
$stepped = new StepIterator($input, 2);

print_r(iterator_to_array($stepped));



