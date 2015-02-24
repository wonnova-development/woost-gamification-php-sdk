# Wonnova PHP SDK

Wonnova SDK for PHP. Use Wonnova's RESTful API directly from your PHP projects

### Installation

The preferred installation method is [composer](https://getcomposer.com). Just run this in your project in order to update your `composer.json` file and install your dependencies.

    composer require wonnova/wonnova-php-sdk ~0.1
    
If you never worked with composer, take a look at its [documentation](https://getcomposer.org/doc/).

### Usage

This library basically provides a simple `Client` object that performs requests to the RESTful API.

```php
include __DIR__ . '/../vendor/autoload.php';

// Create the client and inject a Credentials instance on it with your private key
$wonnovaClient = new \Wonnova\SDK\Connection\Client(
    new \Wonnova\SDK\Auth\Credentials('AaBbCcDd123456')
);

// After this point, you are able to perform requests to the API
try {
    $user = new User();
    $user->setUsername('john.doe')
         ->setProvider('my_company_name')
         ->setFullName('John Doe')
         ->setDateOfBirth(new \DateTime('1980-03-09 18:56:00'))
         ->setEmail('jdoe@doamin.com');
    $wonnovaClient->createUser($user);
    
    // Once the user is created, the userId is populated if it wasn't previously set.
    echo $user->getUserId();
    
    // You can now get the list of your quests
    // The userId can be used if you don't have access to the full User object
    $quests = $wonnovaClient->getUserStatusInQuests($user);
    // This method returns an iterable collection of Quest instances, each one of them with the list of QuestSteps
    foreach ($quests as $quest) {
        echo sprintf('Quest code: %s', $quest->getCode());
        echo sprintf(
            'Quest start date: %s',
            $quest->getStartDate()->format('Y-m-d H:i:s')
        );
        
        // Get the quest steps
        foreach ($quest->getQuestSteps() as $step) {
            echo sprintf('Quest %s. Step type %s', $quest->getName(), $step->getType());
            echo sprintf('Quest %s. Step code %s', $quest->getName(), $step->getCode());
            echo sprintf(
                'Does "%s" completed this step? %s',
                $user->getFullName(),
                ($step->isCompleted() ? 'YES' : 'NO')
            );
        }
    }
} catch (\Wonnova\SDK\Exception\ExceptionInterface $e) {
    echo $e->getTraceAsString();
}
```

The Client object gets another two arguments. The first one is the language in which you want to get responses. It is `es` by default.

The second one is a cache adapter (an instance of `Doctrine\Common\Cache\Cache`). By default a `FilesystemCache` instance is used pointing to your system's temp directory.

This adapter is used to store the authentication token between requests to improve performance. If you have access to something "faster" like Redis, Memcached or OPcache, we recommend you to use another cache adapter.

```php
$memcached = new \Memcached();
$memcached->addServer('127.0.0.1', 11211)
$cacheAdapter = new \Doctrine\Common\Cache\MemecachedCache();
$cacheAdapter->setMemcached($memcached);

$wonnovaClient = new \Wonnova\SDK\Connection\Client(
    new \Wonnova\SDK\Auth\Credentials('AaBbCcDd123456'),
    'es',
    $cacheAdapter
);
```

### Error management

...

### Dependency injection

If you need to depend on Wonnova's Client object, always use the `Wonnova\SDK\Connection\ClientInterface` instead of the concrete `Client` object. This way you will be able to replace the object in case of need.

### RESTful API

https://secure.wonnova.com/tutorial
