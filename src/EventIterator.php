<?php

namespace Gielfeldt\Iterators;

class EventIterator extends ReplaceableIterator
{
    protected $onRewindCallback;
    protected $onFinishedCallback;

    public function onRewind(callable $callback = null)
    {
        $this->onRewindCallback = $callback ? \Closure::fromCallable($callback) : null;
    }

    public function onFinished(callable $callback = null)
    {
        $this->onFinishedCallback = $callback ? \Closure::fromCallable($callback) : null;
    }

    public function rewind()
    {
        parent::rewind();
        if ($this->onRewindCallback) {
            ($this->onRewindCallback)($this, $this->onRewindCallback);
        }
    }

    public function valid()
    {
        $valid = parent::valid();
        if (!$valid && $this->onFinishedCallback) {
            $valid = ($this->onFinishedCallback)($this, $this->onFinishedCallback);
        }
        return $valid;
    }
}
