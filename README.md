# Iterators

[![Build Status](https://travis-ci.org/gielfeldt/iterators.svg?branch=master)][1]
[![Test Coverage](https://codeclimate.com/github/gielfeldt/iterators/badges/coverage.svg)][2]
[![Code Climate](https://codeclimate.com/github/gielfeldt/iterators/badges/gpa.svg)][3]
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gielfeldt/iterators/badges/quality-score.png?b=master)][8]

[![Latest Stable Version](https://poser.pugx.org/gielfeldt/iterators/v/stable.svg)][4]
[![Latest Unstable Version](https://poser.pugx.org/gielfeldt/iterators/v/unstable.svg)][4]
[![Dependency Status](https://www.versioneye.com/user/projects/58ed39a526a5bb0038e422f7/badge.svg?style=flat)][5]
[![License](https://poser.pugx.org/gielfeldt/iterators/license.svg)][6]
[![Total Downloads](https://poser.pugx.org/gielfeldt/iterators/downloads.svg)][4]

[![Documentation Status](https://readthedocs.org/projects/iterators/badge/?version=stable)][7]
[![Documentation Status](https://readthedocs.org/projects/iterators/badge/?version=latest)][7]

## Installation

```
composer require gielfeldt/iterators
```

### Iterators

This library contains a bunch of various iterators that I made primarily for
getting more acquainted with iterators in PHP. Some may be useful. Some may be
silly.

Enjoy!

[ChecksumIterator](#checksumiterator)

[ChunkIterator](#chunkiterator)

[CloningIterator](#cloningiterator)

[CombineIterator](#combineiterator)

[CountableIterator](#countableiterator)

[DiffIterator](#diffiterator)

[FlipIterator](#flipiterator)

[GlobIterator](#globiterator)

[InterleaveIterator](#interleaveiterator)

[IntersectIterator](#intersectiterator)

[KeysIterator](#keysiterator)

[MapIterator](#mapiterator)

[RepeatIterator](#repeatiterator)

[ReplaceableIterator](#replaceableiterator)

[SortIterator](#sortiterator)

[UniqueIterator](#uniqueiterator)

[ValuesIterator](#valuesiterator)

#### ChecksumIterator
Generate a checksum for an iterator, either per iteration or the entire dataset.

```php
use Gielfeldt\Iterators\ChecksumIterator;

$input = new \ArrayIterator([
    ['key1' => 'value1'],
    ['key2' => 'value2'],
    ['key3' => 'value3'],
]);

$iterator = new ChecksumIterator($input, 'md5');
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

var_dump($iterator->getChecksum());

var_dump((string) $iterator);
```

Output:
```
0 => e2e517365ffe6fedd279364e3fa74786
1 => f0a0db0fc9abe193b21fd657fe678884
2 => c04f606bb5bba82282dfa93edb59c6ee

string(32) "4fd19adc845da6fdd9c7c394f4626bac"

string(32) "4fd19adc845da6fdd9c7c394f4626bac"
```

#### ChunkIterator
Split an iterator into chunks of iterators.

```php
use Gielfeldt\Iterators\ChunkIterator;
use Gielfeldt\Iterators\AtomicTempFileObject;

// Split a file into multiple files of a 100 lines each.
$file = new \SplFileObject('inputfile');
foreach (new ChunkIterator($file, 100) as $i => $lines) {
    AtomicTempFileObject::file_put_contents("outputfile.part.$i", implode("", iterator_to_array($lines)));
}
```

#### CloningIterator
Clone each value in iteration.

```php
use Gielfeldt\Iterators\CloningIterator;

$object1 = (object) ['value' => 'test1'];
$object2 = (object) ['value' => 'test2'];
$object3 = (object) ['value' => 'test3'];

$input = new \ArrayIterator([$object1, $object2, $object3]);

$iterator = new CloningIterator($input);
$cloned = iterator_to_array($iterator);
$object1->value = 'MODIFIED';
var_dump(iterator_to_array($input));
var_dump($cloned);
```

Output:
```
array(3) {
  [0] =>
  class stdClass#2 (1) {
    public $value =>
    string(8) "MODIFIED"
  }
  [1] =>
  class stdClass#3 (1) {
    public $value =>
    string(5) "test2"
  }
  [2] =>
  class stdClass#4 (1) {
    public $value =>
    string(5) "test3"
  }
}

array(3) {
  [0] =>
  class stdClass#9 (1) {
    public $value =>
    string(5) "test1"
  }
  [1] =>
  class stdClass#10 (1) {
    public $value =>
    string(5) "test2"
  }
  [2] =>
  class stdClass#11 (1) {
    public $value =>
    string(5) "test3"
  }
}
```

#### CombineIterator
Similar to array_combine(). However, iterators can have non-unique keys. Be aware of
this when using iterator_to_array();

```php
use Gielfeldt\Iterators\CombineIterator;

$keys = new \ArrayIterator(['key1', 'key2', 'key3', 'key1', 'key2', 'key3']);
$values = new \ArrayIterator(['value1', 'value2', 'value3', 'value4', 'value5', 'value6']);

$iterator = new CombineIterator($keys, $values);
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

print_r(iterator_to_array($iterator));
```

Output:
```
key1 => value1
key2 => value2
key3 => value3
key1 => value4
key2 => value5
key3 => value6
Array
(
    [key1] => value4
    [key2] => value5
    [key3] => value6
)
```

#### CountableIterator

Takes any iterator and makes it countable, simply by iterating through it and counting.
```php
use Gielfeldt\Iterators\CountableIterator;

$some_noncountable_iterator = new \IteratorIterator(new \ArrayIterator([1, 2, 3]));
$iterator = new CountableIterator($some_noncountable_iterator);
var_dump(count($iterator));
```

Output:
```
int(3)
```

#### DiffIterator
Compares two iterators. Similar to array_diff(). Possible to set a custom compare
function.

```php
use Gielfeldt\Iterators\DiffIterator;

$input1 = new \ArrayIterator(['key1'  => 'value1', 'key2'  => 'value2', 'key3'  => 'value3']);
$input2 = new \ArrayIterator(['key11' => 'value1', 'key22' => 'value1', 'key2'  => 'value3']);
$input3 = new \ArrayIterator(['key1'  => 'value2', 'key2'  => 'value2', 'key33' => 'value3']);

$iterator = new DiffIterator($input1, $input2, $input3);
$iterator->setDiff(function ($iterator, $key, $value) {
    return $iterator->key() == $key && $iterator->current() == $value;
});
print_r(iterator_to_array($iterator));
```

Output:
```
Array
(
    [key1] => value1
    [key3] => value3
)
```

#### FlipIterator
Similar to array_flip(). However, iterators can have non-unique keys. Be aware of
this when using iterator_to_array();

```php
use Gielfeldt\Iterators\FlipIterator;

$input = new \ArrayIterator([
    'key1'  => 'value1',
    'key2'  => 'value2',
    'key3'  => 'value3',
    'key4'  => 'value1',
    'key5'  => 'value2',
    'key6'  => 'value3',
]);

$iterator = new FlipIterator($input);
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

print_r(iterator_to_array($iterator));
```

Output:
```
value1 => key1
value2 => key2
value3 => key3
value1 => key4
value2 => key5
value3 => key6
Array
(
    [value1] => key4
    [value2] => key5
    [value3] => key6
)
```

#### GlobIterator
Similar to \GlobIterator, but supports **

```php
use Gielfeldt\Iterators\GlobIterator;

$iterator = new GlobIterator('/tmp/**.log');
var_dump(iterator_to_array($iterator));
```

Output:
```
array(2) {
  '/tmp/one.log' =>
  class Gielfeldt\Iterators\GlobIteratorFileInfo#17 (2) {
    private $pathName =>
    string(20) "/tmp/one.log"
    private $fileName =>
    string(10) "one.log"
  }
  '/tmp/somedir/two.log' =>
  class Gielfeldt\Iterators\GlobIteratorFileInfo#16 (2) {
    private $pathName =>
    string(20) "/tmp/somedir/two.log"
    private $fileName =>
    string(15) "two.log"
  }
}
```

#### InterleaveIterator
Interleave multiple iterators.

```php
use Gielfeldt\Iterators\InterleaveIterator;

$input1 = new \ArrayIterator([
    'key1' => 'value11',
    'key2' => 'value12',
    'key3' => 'value13',
]);

$input2 = new \ArrayIterator([
    'key21' => 'value21',
    'key22' => 'value22',
    'key23' => 'value23',
]);

$input3 = new \ArrayIterator([
    'key1' => 'value31',
    'key2' => 'value32',
    'key3' => 'value33',
]);

$iterator = new InterleaveIterator($input1, $input2, $input3);
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

print_r(iterator_to_array($iterator));
```

Output:
```
key1 => value11
key21 => value21
key1 => value31
key2 => value12
key22 => value22
key2 => value32
key3 => value13
key23 => value23
key3 => value33
Array
(
    [key1] => value31
    [key21] => value21
    [key2] => value32
    [key22] => value22
    [key3] => value33
    [key23] => value23
)
```

#### IntersectIterator
Similar to array_intersect(). Possible to set a custom compare function.

```php
use Gielfeldt\Iterators\IntersectIterator;

$input1 = new \ArrayIterator(['key1'  => 'value1', 'key2' => 'value2', 'key3' => 'value3']);
$input2 = new \ArrayIterator(['key11' => 'value1', 'key1' => 'value1', 'key2' => 'value3']);
$input3 = new \ArrayIterator(['key1'  => 'value2', 'key2' => 'value2', 'key1' => 'value1']);

$iterator = new IntersectIterator($input1, $input2, $input3);
$iterator->setDiff(function ($iterator, $key, $value) {
    return $iterator->key() == $key && $iterator->current() == $value;
});
print_r(iterator_to_array($iterator));
```

Output:
```
Array
(
    [key1] => value1
)
```

#### KeysIterator
Similar to array_keys().

```php
use Gielfeldt\Iterators\KeysIterator;

$input = new \ArrayIterator([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
]);

$iterator = new KeysIterator($input);

print_r(iterator_to_array($iterator));
```

Output:
```
Array
(
    [0] => key1
    [1] => key2
    [2] => key3
)
```

#### MapIterator
Similar to array_map().

```php
use Gielfeldt\Iterators\MapIterator;

$input = new \ArrayIterator([
    'key1'  => 'value1',
    'key2'  => 'value2',
    'key3'  => 'value3',
    'key4'  => 'value1',
    'key5'  => 'value2',
    'key6'  => 'value3',
]);

// Flip keys and values.
$iterator = new MapIterator($input, function ($iterator) {
    return [$iterator->current(), $iterator->key()];
});

foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

print_r(iterator_to_array($iterator));
```

Output:
```
value1 => key1
value2 => key2
value3 => key3
value1 => key4
value2 => key5
value3 => key6
Array
(
    [value1] => key4
    [value2] => key5
    [value3] => key6
)
```

#### RepeatIterator
Repeat and iterator n times.

```php
use Gielfeldt\Iterators\RepeatIterator;

$input = new \ArrayIterator([
    'key1'  => 'value1',
    'key2'  => 'value2',
    'key3'  => 'value3',
]);

$iterator = new RepeatIterator($input, 3);

foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

print_r(iterator_to_array($iterator));
```

Output:
```
key1 => value1
key2 => value2
key3 => value3
key1 => value1
key2 => value2
key3 => value3
key1 => value1
key2 => value2
key3 => value3
Array
(
    [key1] => value1
    [key2] => value2
    [key3] => value3
)
```

#### ReplaceableIterator
Just like IteratorIterator but with a setInnerIterator() method.

```php
use Gielfeldt\Iterators\ReplaceableIterator;

$iterator = new ReplaceableIterator(new \ArrayIterator(range(1, 4)));
print_r(iterator_to_array($iterator));

$iterator->setInnerIterator(new \ArrayIterator(range(5, 8)));
print_r(iterator_to_array($iterator));
```

Output:
```
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => 4
)
Array
(
    [0] => 5
    [1] => 6
    [2] => 7
    [3] => 8
)
```

#### SortIterator

```php
use Gielfeldt\Iterators\SortIterator;

$input = new \ArrayIterator([6, 3, 2, 7, 1, 9]);
$iterator = new SortIterator($input);
print_r(iterator_to_array($iterator));

$input = new \ArrayIterator([6, 3, 2, 7, 1, 9]);
$iterator = new SortIterator($input, SortIterator::SORT_DESC);
print_r(iterator_to_array($iterator));

$input = new \ArrayIterator([6, 3, 2, 7, 1, 9]);
$iterator = new SortIterator($input, SortIterator::SORT_ASC, SortIterator::SORT_REINDEX);
print_r(iterator_to_array($iterator));

$input = new \ArrayIterator([6, 3, 2, 7, 1, 9]);
$iterator = new SortIterator($input, SortIterator::SORT_ASC, SortIterator::SORT_REINDEX, function ($a, $b) {
    return -$a->current <=> -$b->current;
});
print_r(iterator_to_array($iterator));
```

Output:
```
Array
(
    [4] => 1
    [2] => 2
    [1] => 3
    [0] => 6
    [3] => 7
    [5] => 9
)
Array
(
    [5] => 9
    [3] => 7
    [0] => 6
    [1] => 3
    [2] => 2
    [4] => 1
)
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => 6
    [4] => 7
    [5] => 9
)
Array
(
    [0] => 9
    [1] => 7
    [2] => 6
    [3] => 3
    [4] => 2
    [5] => 1
)
```

#### UniqueIterator
Similar to array_unique(). Also supports a custom callback function.

```php
use Gielfeldt\Iterators\UniqueIterator;

$input = new \ArrayIterator([-4, -3, -2, -1, 0, 1, 2, 3, 5]);

// Unique elements by their square.
$iterator = new UniqueIterator($input, UniqueIterator::REINDEX, function ($iterator) {
    return $iterator->current() * $iterator->current();
});

print_r(iterator_to_array($iterator));
```

Output:
```
Array
(
    [0] => -4
    [1] => -3
    [2] => -2
    [3] => -1
    [4] => 0
    [8] => 5
)
```

#### ValuesIterator
Similar to array_vales().

```php
use Gielfeldt\Iterators\ValuesIterator;

$input = new \ArrayIterator([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
]);

$iterator = new ValuesIterator($input);

print_r(iterator_to_array($iterator));
```

Output:
```
Array
(
    [0] => value1
    [1] => value2
    [2] => value3
)
```

### Not iterators as such ...
These are more an extension of the SPL file handling.

[CsvFileObject](#csvfileobject)

[AtomicTempFileObject](#atomictempfileobject)

[AtomicTempFileObjects](#atomictempfileobjects)

#### CsvFileObject
An extension of SplFileObject in csv mode, but with csv header support.

```php
use Gielfeldt\Iterators\CsvFileObject;

// Load csv file and dump it.
$file = new CsvFileObject('somefile.csv');
print_r(iterator_to_array($file));

// Same but csv comes via a string variable.
$csvdata = "Columm1,Column2\nValue1,Value2\nValue3,Value4";
$file = new CsvFileObject('data://application/octet,' . $csvdata);
print_r(iterator_to_array($file));
```

Output:
```
Array
(
    [0] => Array
        (
            [Columm1] => Value1
            [Column2] => Value2
        )

    [1] => Array
        (
            [Columm1] => Value3
            [Column2] => Value4
        )

)
Array
(
    [0] => Array
        (
            [Columm1] => Value1
            [Column2] => Value2
        )

    [1] => Array
        (
            [Columm1] => Value3
            [Column2] => Value4
        )

)
```

#### AtomicTempFileObject

#### AtomicTempFileObjects


### Helpers
Contains various helper methods.

#### Iterator
```php
use Gielfeldt\Iterators\Iterator;

$input = new \ArrayIterator([1,2,3,4,5,6]);
var_dump(Iterator::sum($input));
var_dump(Iterator::product($input));
var_dump(Iterator::average($input));
var_dump(Iterator::min($input));
var_dump(Iterator::max($input));
```

Output:
```
int(21)

int(720)

double(3.5)

int(1)

int(6)
```

### Caveats

1. Lots probably.


[1]:  https://travis-ci.org/gielfeldt/iterators
[2]:  https://codeclimate.com/github/gielfeldt/iterators/coverage
[3]:  https://codeclimate.com/github/gielfeldt/iterators
[4]:  https://packagist.org/packages/gielfeldt/iterators
[5]:  https://www.versioneye.com/user/projects/58ed39a526a5bb0038e422f7
[6]:  https://github.com/gielfeldt/iterators/blob/master/LICENSE.md
[7]:  https://readthedocs.org/projects/iterators/?badge=latest
[8]:  https://scrutinizer-ci.com/g/gielfeldt/iterators/?branch=master
