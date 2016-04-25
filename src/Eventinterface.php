<?php
namespace Eventee;

interface EventInterface
{
    public function execute(callable $listener);

    /**
     * Stops event propagation
     * @return null
     */
    public function stop();

    /**
     * Checks if event propagation has been stopped
     * @return boolean
     */
    public function isStopped();
}
