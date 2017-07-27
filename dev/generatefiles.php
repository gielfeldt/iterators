<?php

function create_files($path, $count) {
    for ($i = 0; $i < $count; $i++) {
        $file = "$path/file.$i.txt";
        print "$file\r";
        touch($file);
    }
}

mkdir('files');
for ($i = 0; $i < 100; $i++) {
    $path = "files/files.$i";
    print "$path\n";
    mkdir($path);
    create_files($path, 10000);
    print "\n";
}
