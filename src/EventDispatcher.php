<?php
namespace Eventee;

use InvalidArgumentException;

class EventDispatcher
{
    private $listeners = [];

    /**
     * Searches for all listeners observing the event and executes
     * them one by one.
     *
     * @param EventInterface $event
     * @return bool true if all listeners where executed otherwise false
     */
    public function dispatch(EventInterface $event)
    {
        foreach ($this->listeners as $className => $listenerList) {
            if ($event instanceof $className) {
                foreach ($listenerList as $listener) {
                    if ($event->isStopped()) {
                        return false;
                    }
                    $event->execute($listener);
                }
            }
        }
        return true;
    }

    /**
     * Binds listener to an event which has to be a string containing class name which
     * implements `Eventee\EventInterface`
     *
     * @param $event
     * @param callable $listener
     */
    public function addListener($event, callable $listener)
    {
        if (! is_a($event, EventInterface::class, true)) {
            throw new InvalidArgumentException(sprintf(
                '%s::addListener passed $event argument must implement %s',
                get_called_class(),
                EventInterface::class
            ));
        }

        if (! isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }

        $this->listeners[$event][$this->getListenerSignature($listener)] = $listener;
    }

    public function removeListener($event, callable $listener)
    {
        if ($this->hasListener($event, $listener)) {
            unset($this->listeners[$event][$this->getListenerSignature($listener)]);
            return true;
        }
        return false;
    }

    public function hasListener($event, callable $listener)
    {
        return isset($this->listeners[$event][$this->getListenerSignature($listener)]);
    }

    private function getListenerSignature(callable $listener)
    {
        if (is_array($listener)) {
            return join('.', $listener);
        } elseif (is_string($listener)) {
            return $listener;
        } else {
            return spl_object_hash((object) $listener);
        }
    }
}
