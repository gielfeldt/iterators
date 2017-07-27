<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\MapIterator;
use Gielfeldt\Iterators\UniqueIterator;
use Gielfeldt\Iterators\RecursiveUniqueIterator;

class Agg implements IteratorAggregate {
    public function __construct($iterator) {
        $this->iterator = $iterator;
    }

    public function getIterator() {
        return $this->iterator;
    }
}

$a = [
    '0:test1',
    '0:test2',
    '0:test6',
    '0:test2',
    '0:test3' => [
        '1:test4',
        '1:test5',
        '1:test6',
        '1:test6',
        '1:test7' => [
            '2:test8',
            '2:test9' => [
                '3:test10',
            ],
        ],
    ],
];

$i = new ArrayIterator($a);
$r = new RecursiveArrayIterator($a);
$r = new RecursiveUniqueIterator($r);

$agg = new Agg($r);
$ri = new RecursiveIteratorIterator($agg, RecursiveIteratorIterator::CHILD_FIRST);
#$ri = new RecursiveIteratorIterator($agg, RecursiveIteratorIterator::SELF_FIRST);
#$ri = new RecursiveIteratorIterator($agg);
#var_dump(iterator_to_array($ri));
#exit;
var_export(iterator_to_array(new MapIterator($ri, function ($iterator) {
    $i = str_repeat(' ', $iterator->getDepth());
    static $key = 0;
    $value = $iterator->current();
    $value = is_scalar($value) ? $value : serialize($value);
    print "$i$key => $value\n";
    return [$key++, $value];
})));

/*
foreach ($ri as $k => $v) {
    $i = str_repeat(' ', $ri->getDepth());
    $v = is_scalar($v) ? $v : 'Array';
    print "$i$k => $v\n";
}
*/
