<?php

namespace Gielfeldt\Iterators;

/**
 * Provide fielded rows of an array from a csv file.
 *
 * Input:
 * [
 *   ['column1 header', 'column2 header', 'column3 header'],
 *   ['value 1', 'value 2', 'value 3'],
 *   ['value 4', 'value 5', 'value 6'],
 * ]
 *
 * Output:
 * [
 *   ['column1 header' => 'value 1', 'column2 header' => 'value 2', 'column3 header' => 'value 3'],
 *   ['column1 header' => 'value 4', 'column2 header' => 'value 5', 'column3 header' => 'value 6'],
 * ]
 */
class CsvFileObject extends \SplFileObject implements \JsonSerializable, \Countable
{
    /**
     * The fields of a row.
     *
     * @var array
     */
    protected $fields;

    public $rows;

    /**
     * SplFileObject constructor.
     *
     * Setup SplFileObject in CSV read-mode.
     */
    public function __construct($filename, $open_mode = "r", $use_include_path = false, $context = null)
    {
        parent::__construct($filename, $open_mode, $use_include_path, $context);
        $this->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $this->processHeader();
    }

    /**
     * Get determined fields for this csv file.
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Fetch fields from the current row.
     *
     * This method should only be called when file is rewound.
     */
    protected function processHeader()
    {
        $this->fields = array_values(parent::current());
        parent::next();
    }

    /**
     * Rewind file and fetch fields from first row.
     */
    public function rewind()
    {
        parent::rewind();
        $this->processHeader();
    }

    /**
     * Map row into fields.
     */
    public function current()
    {
        $row = parent::current();
        $fieldedRow = [];
        foreach ($this->fields as $idx => $field) {
            $fieldedRow[$field] = $row[$idx] ?? null;
        }
        return $fieldedRow;
    }

    public function valid()
    {
        $valid = parent::valid();
        if (!$valid && !isset($this->rows) && !is_null($this->key())) {
            $this->rows = $this->key();
        }
        return $valid;
    }

    /**
     * Subtract 1 row from index, so that we don't include the header in our count.
     */
    public function key()
    {
        return parent::key() - 1;
    }

    /**
     * Add 1 to skip the header when seeking.
     */
    public function seek($position)
    {
        if ($position < 0) {
            throw new \LogicException("Can't seek file " . $this->getFilename() . " to negative line $position");
        }
        return parent::seek($position + 1);
    }

    /**
     * Implements jsonSerialize.
     */
    public function jsonSerialize()
    {
        return iterator_to_array($this);
    }

    /**
     * Implements Countable.
     */
    public function count()
    {
        if (!isset($this->rows)) {
            $this->seek($this->getSize());
            $this->rows = $this->key();
        }
        return $this->rows;
    }
}
