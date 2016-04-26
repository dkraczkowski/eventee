# Eventee [![build status](https://travis-ci.org/dkraczkowski/eventee.svg?branch=master)](https://travis-ci.org/dkraczkowski/eventee) [![License](https://poser.pugx.org/eventee/eventee/license.svg)](https://packagist.org/packages/eventee/eventee)

Eventee is a minimalistic and powerfull event dispatching library for PHP.

## TOC

- [`Installation`](#installation)
- [`Listening to an event`](#listening-to-an-event)
- [`Dispatching an event`](#dispatching-an-event)
- [`Creating custom event`](#creating-custom-event)
- [`Stopping event from propagation`](#stopping-event-from-propagation)
- [`Checking if listener exists`](#checking-if-listener-exists)
- [`Removing listener`](#removing-listener)


## Installation

Make sure you have composer installed _(More information [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx))_. And your php version is >= PHP 5.5.

```
composer require eventee/eventee
```


## Listening to an event

```php
$hub = new \Eventee\EventHub();
$hub->addListener(\Eventee\Event::class, function() {
    echo 'Hello world!';
});
```

## Dispatching an event
```php
$hub = new \Eventee\EventHub();
$hub->addListener(\Eventee\Event::class, function() {
    echo 'Hello world!';
});
// Will echo 'Hello World'.
$hub->dispatch(new \Eventee\Event());
```

## Creating custom event
```php
class OnUserCreated extends Event
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}

$hub = new \Eventee\EventHub();
$hub->addListener(OnUserCreated::class, function(OnUserCreated $e) {
    echo sprintf('Hello world, %s!', $e->getUser());
});
// Will echo 'Hello World, John!'.
$hub->dispatch(new OnUserCreated('John'));
```

## Stopping event from propagation

```php
$hub = new \Eventee\EventHub();
$hub->addListener(\Eventee\Event::class, function(Event $e) {
    $e->stop();
    echo 'Hello ';
});
$hub->addListener(\Eventee\Event::class, function(Event $e) {
    echo 'World';
});
// Will echo 'Hello '.
$hub->dispatch(new \Eventee\Event());
```

## Checking if listener exists

```php
$hub = new \Eventee\EventHub();
$listener = function(Event $e) {
    echo 'Hello ';
}
$hub->addListener(\Eventee\Event::class, $listener);

// Will echo 'Hello world!'
if ($hub->hasListener(\Eventee\Event::class, $listener) {
    echo 'Hello World!';
}
```

## Removing listener

```php
$hub = new \Eventee\EventHub();
$listener = function(Event $e) {
    echo 'Hello ';
}
$hub->addListener(\Eventee\Event::class, $listener);

$hub->removeListener(\Eventee\Event::class, $listener);

// No output this time.
if ($hub->hasListener(\Eventee\Event::class, $listener) {
    echo 'Hello World!';
}
```


