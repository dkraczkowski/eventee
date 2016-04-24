<?php
namespace EventeeTest;

use Eventee\Event;
use Eventee\EventHub;

use EventeeTest\Fixtures\BaseEvent;
use EventeeTest\Fixtures\TestEvent;

class EventHubTest extends \PHPUnit_Framework_TestCase
{
    public function testCanListenToAnEvent()
    {
        $hub = new EventHub();
        $listener = function(Event $e) {};
        $listener2 = function(Event $e) {};

        $hub->addListener(Event::class, $listener);
        $hub->addListener(Event::class, $listener2);
        $hub->addListener(BaseEvent::class, $listener);

        self::assertTrue($hub->hasListener(Event::class, $listener));
        self::assertTrue($hub->hasListener(Event::class, $listener2));
        self::assertTrue($hub->hasListener(BaseEvent::class, $listener));
        self::assertFalse($hub->hasListener(BaseEvent::class, $listener2));

        $hub->removeListener(Event::class, $listener);
        self::assertFalse($hub->hasListener(Event::class, $listener));
        self::assertTrue($hub->hasListener(Event::class, $listener2));
        $hub->removeListener(Event::class, $listener2);
        self::assertFalse($hub->hasListener(Event::class, $listener));
        self::assertFalse($hub->hasListener(Event::class, $listener));
    }

    public function testCanDispatchAnEvent()
    {
        $generalEventCalls = 0;
        $testEventCalls = 0;
        $hub = new EventHub();
        $generalEventListener = function(Event $e) use(&$generalEventCalls) {
            ++$generalEventCalls;
        };

        $testEventListener = function(Event $e) use(&$testEventCalls) {
            ++$testEventCalls;
        };

        $hub->addListener(Event::class, $generalEventListener);
        $hub->addListener(TestEvent::class, $testEventListener);
        $hub->dispatch(new Event());
        $hub->dispatch(new TestEvent());
        self::assertEquals(2, $generalEventCalls);
        self::assertEquals(1, $testEventCalls);

    }

    public function testCanCancelAnEvent()
    {
        $testEventCalls = 0;

        $cancelingListener = function(Event $e) {
            $e->stop();
        };

        $testListener = function(Event $e) use(&$testEventCalls) {
            ++$testEventCalls;
        };

        $hub = new EventHub();
        $hub->addListener(Event::class, $cancelingListener);
        $hub->addListener(Event::class, $testListener);
        $hub->dispatch(new Event());

        self::assertEquals(0, $testEventCalls);
    }
}
