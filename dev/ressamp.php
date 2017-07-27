<?php

$dataSize = 20;
$data = [];

// Populate data with $dataSize random integers

for($i = 0; $i < $dataSize; $i++) {
    $data[] = $i;
}

// Take sample of size $k from $data
// Samples stored in $result

$k = 4;
$result = [];

// Create reservoir

for($i = 0; $i < $k; $i++)
  $result[$i] = $data[$i];

echo implode(' - ',array_keys($result))."\n";
echo implode(' - ',$result)."\n";

// Iterate from the (k+1)th to the nth element and
// replace elements in reservoir with a decreasing probability

for(; $i < $dataSize; $i++) {

  $r = (int) rand(0, $i);
  print "$r < $k\n";
  if($r < $k) {
    $result[$r] = $data[$i];
  }
}

echo implode(' - ',array_keys($result))."\n";
echo implode(' - ',$result)."\n";
