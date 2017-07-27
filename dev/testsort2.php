<?php

require './vendor/autoload.php';

use Gielfeldt\Iterators\PersistedIterator;
use Gielfeldt\Iterators\AtomicTempFileObject;
use Gielfeldt\Iterators\CsvFileObject;
use Gielfeldt\Iterators\ChunkIterator;
use Gielfeldt\Iterators\MapIterator;
use Gielfeldt\Iterators\SortIterator;
use Gielfeldt\Iterators\MergeSortIterator;
use Gielfeldt\Iterators\AppendIterator;
use Gielfeldt\Iterators\ShuffleIterator;

use JBZoo\Profiler\Benchmark;
/*
$a = new \ArrayIterator(range(1, 100));
$p = new ShuffleIterator($a);
print_r(iterator_to_array($p));
$p2 = new \Gielfeldt\Iterators\ShuffleIterator2($a);
print_r(iterator_to_array($p2));
#exit;

Benchmark::compare([
    'arrayiterator' => function () use ($a) {
        iterator_to_array($a, true);
    },
    'shuffleiterator' => function () use ($p) {
        iterator_to_array($p, true);
    },
    'shuffleiterator2' => function () use ($p2) {
        iterator_to_array($p2, true);
    }
], ['count' => 1000]);
exit;
/**/

/**/
#$a = new \ArrayIterator(['test1' => 'hest2', 'test2' => ['hest3', 'fest4']]);
$a = new \ArrayIterator(range(1, 100));
$p = new PersistedIterator($a);
$p2 = new \Gielfeldt\Iterators\PersistedIterator2($a);
#print_r(iterator_to_array($p, true));
#$p->add('zxczxc', (object) ['dfgdgfdg' => 12345]);
#print_r(iterator_to_array($p, true));

Benchmark::compare([
    'arrayiterator' => function () use ($a) {
        iterator_to_array($a, true);
    },
    'persistediterator' => function () use ($p) {
        iterator_to_array($p, true);
    },
    'persistediterator2' => function () use ($p2) {
        iterator_to_array($p2, true);
    }
], ['count' => 1000]);

exit;
/**/

/*
$a[] = range(1, 10);
$a[] = range(11, 20);
$a[] = range(21, 30);
$a[] = range(31, 40);
$a[] = range(41, 50);
shuffle($a);
foreach ($a as &$b) shuffle($b);
#$i = new AppendIterator($a);
$i = new MergeSortIterator(new MapIterator($a, function($iterator) {
    return new SortIterator($iterator->current());
}));
$i = new PersistedIterator($i);
foreach ($i as $k => $v) {
    print "$k => $v\n";
}
exit;
*/
/*
$a = range(1,1000000);
shuffle($a);
$csvFile = new AtomicTempFileObject('100000.csv');
$csvFile->fputcsv(['value']);
foreach ($a as $i) $csvFile->fputcsv([$i]);
$csvFile->persistOnClose(AtomicTempFileObject::PERSIST_UNCHANGED);
exit;
/**/

#shuffle($a);

$GLOBALS['sortFunc'] = function ($a, $b) {
    return $a->current['value'] <=> $b->current['value'];
};

$csvFile = new CsvFileObject('1000.csv');
$chunkIterator = new ChunkIterator($csvFile, 1000);
$persistedChunks = new MapIterator($chunkIterator, function ($iterator) {
    print ".";
    #return $iterator->current();
    $sortedChunk = new SortIterator($iterator->current(), $GLOBALS['sortFunc']);
    #return $sortedChunk;
    $persistedChunk = new PersistedIterator($sortedChunk);
    return $persistedChunk;
});

$sorted = new MergeSortIterator($persistedChunks, $GLOBALS['sortFunc']);
#$a = new \AppendIterator();
#foreach ($persistedChunks as $persistedChunk) $a->append($persistedChunk->getIterator());
#exit;

foreach ($sorted as $row) {
    print $row['value'] . "\n";
}
exit;

$i = new \ArrayIterator($a);
$p = new PersistedIterator($i);

$i1 = $p->getIterator();
$i2 = $p->getIterator();

unset($p);
#unset($i1);
#unset($i2);

#die("ASDSA");

foreach ($i1 as $key => $value) {
    print "$key => $value\n";
}

foreach ($i2 as $key => $value) {
    print "$key => $value\n";
}
