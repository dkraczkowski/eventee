<?php
namespace Eventee;

class Event implements EventInterface
{
    private $stopped = false;
    protected $caller;

    public function __construct($caller = null)
    {
        $this->caller = $caller;
    }

    public function execute(callable $listener)
    {
        $listener($this);
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
