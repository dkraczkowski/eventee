<?php
namespace Eventee;

use InvalidArgumentException;

class EventHub
{
    protected $listeners = [];

    public function dispatch(EventInterface $event)
    {
        foreach ($this->listeners as $className => $listenerList) {
            if (is_subclass_of($event, $className) || $event instanceof $className) {
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

    public function addListener($event, callable $callable)
    {
        if (! in_array(EventInterface::class, class_implements($event))) {
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
        $this->performActionForListener($event, $callable, function($ignoreThisArgument, $index) use ($event) {
            array_splice($this->listeners[$event], $index, 1);
            return true;
        });
    }

    public function hasListener($event, callable $callable)
    {
        return $this->performActionForListener($event, $callable, function() { return true; });
    }

    private function performActionForListener($event, callable $callable, callable $action)
    {
        if (! isset($this->listeners[$event])) {
            return false;
        }

        for ($i = 0, $l = count($this->listeners[$event]); $i < $l; $i++) {
            $current = $this->listeners[$event][$i];
            if ($current === $callable) {
                return $action($current, $i);
            }
        }

        return false;
    }
}
