# Eventee
Eventee is a minimalistic and powerfull event dispatching library for PHP.

## Installation
...

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
$hub->dispatch(new \Eventee\Event());// Will echo 'Hello World'
```

