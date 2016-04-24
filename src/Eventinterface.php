<?php
namespace Eventee;

interface EventInterface
{
    /**
     * Calls listener which is listening to this event.
     * Should return true in order to EventHub execute next
     * listener in queue.
     *
     * @param callable $listener
     * @return boolean
     */
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
