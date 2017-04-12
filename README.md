# Iterators

[![Build Status](https://travis-ci.org/gielfeldt/iterators.svg?branch=master)][1]
[![Test Coverage](https://codeclimate.com/github/gielfeldt/iterators/badges/coverage.svg)][2]
[![Code Climate](https://codeclimate.com/github/gielfeldt/iterators/badges/gpa.svg)][3]

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
getting more acquainted with iterators in PHP. They might be of use to someone.
So here you go.

[ChecksumIterator](#checksumiterator)

[CloningIterator](#cloningiterator)

[CombineIterator](#combineiterator)

[CountableIterator](#countableiterator)

[DiffIterator](#diffiterator)

[FlipIterator](#flipiterator)

[GlobIterator](#globiterator)

[IntersectIterator](#intersectiterator)

[KeysIterator](#keysiterator)

[MapIterator](#mapiterator)

[RepeatIterator](#repeatiterator)

[SortIterator](#sortiterator)

[UniqueIterator](#uniqueiterator)

[ValuesIterator](#valuesiterator)

[ZipIterator](#zipiterator)

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

var_dump(iterator_to_array($iterator));
```

Output:
```
key1 => value1
key2 => value2
key3 => value3
key1 => value4
key2 => value5
key3 => value6

array(3) {
  'key1' =>
  string(6) "value4"
  'key2' =>
  string(6) "value5"
  'key3' =>
  string(6) "value6"
}
```

#### CountableIterator

Takes any iterator and makes it countable, simply by iterating through it and counting.
```php
use Gielfeldt\Iterators\DiffIterator;

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
var_dump(iterator_to_array($iterator));
```

Output:
```
array(2) {
  'key1' =>
  string(6) "value1"
  'key3' =>
  string(6) "value3"
}
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

var_dump(iterator_to_array($iterator));
```

Output:
```
value1 => key1
value2 => key2
value3 => key3
value1 => key4
value2 => key5
value3 => key6

array(3) {
  'value1' =>
  string(4) "key4"
  'value2' =>
  string(4) "key5"
  'value3' =>
  string(4) "key6"
}
```

#### GlobIterator

#### IntersectIterator

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

var_dump(iterator_to_array($iterator));
```

Output:
```
array(3) {
  [0] =>
  string(4) "key1"
  [1] =>
  string(4) "key2"
  [2] =>
  string(4) "key3"
}
```

#### MapIterator

#### RepeatIterator

#### SortIterator

#### UniqueIterator

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

var_dump(iterator_to_array($iterator));
```

Output:
```
array(3) {
  [0] =>
  string(4) "value1"
  [1] =>
  string(4) "value2"
  [2] =>
  string(4) "value3"
}
```

#### ZipIterator
"zip" multiple iterators together.

```php
use Gielfeldt\Iterators\ZipIterator;

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

$iterator = new ZipIterator($input1, $input2, $input3);
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

var_dump(iterator_to_array($iterator));
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

array(6) {
  'key1' =>
  string(7) "value31"
  'key21' =>
  string(7) "value21"
  'key2' =>
  string(7) "value32"
  'key22' =>
  string(7) "value22"
  'key3' =>
  string(7) "value33"
  'key23' =>
  string(7) "value23"
}
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
var_dump(iterator_to_array($file));

// Same but csv comes via a string variable.
$csvdata = "Columm1,Column2\nValue1,Value2\nValue3,Value4";
$file = new CsvFileObject('data://application/octet,' . $csvdata);
var_dump(iterator_to_array($file));
```

Output:
```
array(2) {
  [0] =>
  array(2) {
    'Columm1' =>
    string(6) "Value1"
    'Column2' =>
    string(6) "Value2"
  }
  [1] =>
  array(2) {
    'Columm1' =>
    string(6) "Value3"
    'Column2' =>
    string(6) "Value4"
  }
}

array(2) {
  [0] =>
  array(2) {
    'Columm1' =>
    string(6) "Value1"
    'Column2' =>
    string(6) "Value2"
  }
  [1] =>
  array(2) {
    'Columm1' =>
    string(6) "Value3"
    'Column2' =>
    string(6) "Value4"
  }
}
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
```

Output:
```
int(21)

int(720)

double(3.5)
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
