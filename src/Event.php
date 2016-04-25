<?php
namespace Eventee;

class Event implements EventInterface
{
    protected $backtree = [];
    private $stopped = false;
    // @todo: constructor should accept caller
    public function execute(callable $listener)
    {
        // @todo: move to event hub
        if ($this->isStopped()) {
            return false;
        }
        $listener($this);
        $this->backtree[] = $listener;
        return true;
    }

    public function stop()
    {
        $this->stopped = true;
    }

    public function isStopped()
    {
        return $this->stopped;
    }
}
