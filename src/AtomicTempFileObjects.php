<?php

namespace Gielfeldt\Iterators;

/**
 * Create multiple atomic temp files.
 */
class AtomicTempFileObjects
{
    protected $files = [];

    /**
     * Constructor.
     *
     * @param array $files
     *   The files to use.
     */
    public function __construct($files = [])
    {
        $this->files = $files;
    }

    /**
     * Get opened files.
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Get an already opened file.
     *
     * @param string $fileName
     *   The name of the file to get.
     *
     * @return AtomicTempFileObject
     *   The open file.
     */
    public function getFile($fileName): AtomicTempFileObject
    {
        if (!$this->isFileOpen($fileName)) {
            throw new \RuntimeException("File: $fileName not opened!");
        }
        return $this->files[$fileName];
    }

    /**
     * Check if file is opened.
     *
     * @param string $fileName
     *   The name of the file to check.
     *
     * @return boolean
     *   True if opened, false if not.
     */
    public function isFileOpen($fileName): bool
    {
        return isset($this->files[$fileName]);
    }

    /**
     * Open a new atomic temp file.
     *
     * @param string $fileName
     *   The name of the file to open.
     *
     * @return AtomicTempFileObject
     *   The file opened.
     */
    public function openFile($fileName): AtomicTempFileObject
    {
        if ($this->isFileOpen($fileName)) {
            throw new \RuntimeException("File: $fileName already opened!");
        }
        $this->files[$fileName] = new AtomicTempFileObject($fileName);
        return $this->files[$fileName];
    }

    /**
     * Add an already opened AtomicTempFileObject file.
     *
     * @param AtomicTempFileObject $file
     */
    public function addFile($file): AtomicTempFileObjects
    {
        $realPath = $file->getDestinationRealPath();
        if ($this->isFileOpen($realPath)) {
            throw new \RuntimeException("File: " . $realPath . " already opened!");
        }
        $this->files[$realPath] = $file;
        return $this;
    }

    /**
     * Split a csv file into multiple csv files.
     *
     * @param  Iterator $input
     *   The input to split. Each row must contain an array of key value pairs.
     * @param  callable $callback
     *   A callback returning the filename for the specific row.
     * @return
     */
    public function splitCsvFile(\Iterator $input, callable $callback)
    {
        $callback = \Closure::fromCallable($callback);
        $this->process(
            $input,
            function ($row, $rowNum, $input, $output) use ($callback) {
                if ($fileName = $callback($row)) {
                    if (!$output->isFileOpen($fileName)) {
                        $output->openFile($fileName)->fputcsv(array_keys($row));
                    }
                    $output->getFile($fileName)->fputcsv(array_values($row));
                }
            }
        );
        return $this;
    }

    /**
     * Easy access iterator apply for processing an entire file.
     *
     * @param Iterator $input
     *   The input to split.
     * @param callable $callback
     *   Callback for each item in iterator.
     */
    public function process(\Iterator $input, callable $callback)
    {
        $callback = \Closure::fromCallable($callback);
        $input->rewind();
        iterator_apply(
            $input,
            function (\Iterator $iterator) use ($callback) {
                $callback($iterator->current(), $iterator->key(), $iterator, $this);
                return true;
            },
            [$input]
        );
        return $this;
    }

    /**
     * Dispatch method calls to all attached AtomicTempFileObject.
     */
    public function __call($method, $arguments)
    {
        foreach ($this->files as $file) {
            call_user_func_array([$file, $method], $arguments);
        }
        return $this;
    }
}
