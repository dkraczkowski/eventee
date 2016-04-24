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

## Creating a custom event
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
    echo sprintf('Hello world, %s!', $e->getUser();
});
$hub->dispatch(new OnUserCreated('John'));// Will echo 'Hello World, John!'
```

