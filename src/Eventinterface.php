<?php
namespace Eventee;

/**
 * Interface EventInterface
 * @package Eventee
 */
interface EventInterface
{
    public function execute(callable $listener);
    public function stop();
    public function isStopped();
}
