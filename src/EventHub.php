<?php
namespace Eventee;

use InvalidArgumentException;

class EventHub
{
    protected $listeners = [];

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
                    if ($event->execute($listener)) {
                        continue;
                    }
                    return false;
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
     * @param callable $callable
     */
    public function addListener($event, callable $callable)
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

        $this->listeners[$event][] = $callable;
    }

    public function removeListener($event, callable $callable)
    {
        $index = $this->findListenerIndex($event, $callable);
        if ($index === false) {
            return false;
        }

        array_splice($this->listeners[$event], $index, 1);
        return true;
    }

    public function hasListener($event, callable $callable)
    {
        return $this->findListenerIndex($event, $callable) !== false;
    }

    private function findListenerIndex($event, callable $callable)
    {
        if (! isset($this->listeners[$event])) {
            return false;
        }

        for ($i = 0, $l = count($this->listeners[$event]); $i < $l; $i++) {
            $current = $this->listeners[$event][$i];
            if ($current === $callable) {
                return $i;
            }
        }

        return false;
    }
}
