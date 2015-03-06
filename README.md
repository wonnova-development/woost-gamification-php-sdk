# Wonnova - Woost gamification PHP SDK

Woost SDK for PHP. Use [Woost](http://www.wonnova.com/woost)'s RESTful API directly from your PHP projects

### Installation

The preferred installation method is [composer](https://getcomposer.com). Just run this in your project in order to update your `composer.json` file and install your dependencies.

    composer require wonnova/woost-gamification-php-sdk ~0.1
    
If you never worked with composer, take a look at its [documentation](https://getcomposer.org/doc/).

### Usage

This library basically provides a simple `Client` object that performs requests to the RESTful API.

```php
include __DIR__ . '/../vendor/autoload.php';

// Create the client and inject a Credentials instance on it with your private key
$wonnovaClient = new \Wonnova\SDK\Connection\Client(
    new \Wonnova\SDK\Auth\Credentials(['key' => 'AaBbCcDd123456'])
);

// After this point, you are able to perform requests to the API
try {
    // Create a new user in your system
    $user = new User();
    $user->setUsername('john.doe')
         ->setProvider('my_company_name')
         ->setFullName('John Doe')
         ->setDateOfBirth(new \DateTime('1980-03-09 18:56:00'))
         ->setEmail('jdoe@doamin.com');
    $wonnovaClient->createUser($user);
    
    // Once the user is created, the userId is populated if it wasn't previously set.
    echo $user->getUserId();
    
    // Make the user perform an action
    // The userId can be used if you don't have access to the full User object
    $actionCode = 'LOGIN';
    $wonnovaClient->notifyAction($user, $actionCode);
    
    // You can now get the list of your quests and the status of the user in each one of them
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
            echo sprintf('Quest %s. Step type: %s', $quest->getName(), $step->getType());
            echo sprintf('Quest %s. Step code: %s', $quest->getName(), $step->getCode());
            echo sprintf(
                'Did "%s" complete this step? %s',
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
    new \Wonnova\SDK\Auth\Credentials(['key' => 'AaBbCcDd123456']),
    'es',
    $cacheAdapter
);
```

### Error management

Each method in the `Client` object will perform at least one HTTP request (even more if a reauthentication has to be performed). If the server returns a response with 200 status code, the response content will be parsed, but 4xx and 5xx status codes could throw an exception.

* **401**: This could be returned when the authentication token is invalid. In that case the client will automatically reauthenticate. If the server returns this status but it is not an INVALID_TOKEN response, then a `Wonnova\SDK\Exception\UnauthorizedException` will be thrown.
* **400**: This status is returned when an invalid request is performed for some reason, like invalid arguments and such. This case will throw a `Wonnova\SDK\Exception\InvalidRequestException`.
* **404**: If for some reason the requested URL does not exist and a 404 error is returned, a `Wonnova\SDK\Exception\NotFoundException` will be thrown. In a normal situation this shouldn't happen unless you manually perform a request to a custom route.
* **500**: Of course, if there is a server error, a `Wonnova\SDK\Exception\ServerException` will be thrown.
* **Others**: Other status codes shouldn't be returned, but in case something went wrong, a `Wonnova\SDK\Exception\RuntimeException` will be thrown.

All the 4xx exceptions extend from a common `Wonnova\SDK\Exception\ClientException`, and all the exceptions in this package implement the common `Wonnova\SDK\Exception\ExceptionInterface` to ease catching them.

### Future compatibility

If for some reason Wonnova has to publish a new version of the API with new endpoints before a new version of this SDK is published and you need to consume those new endpoints, there is a way to do it.

Using the public `connect` method, you will get a raw response of any route request. You won't get mapped objects, but you will be able to manually parse the response and work with it.

```php
$method = 'GET';
$route = '/foo/bar/' . $userId;

// This response object is a GuzzleHttp\Message\ResponseInterface instance
$response = $wonnovaClient->connect($method, $route);
$data = json_decode($response->getBody()->getContents(), true);

// To send information in the body, like in POST and PUT requests, use the third argument like this
$response = $wonnovaClient->connect('POST', '/resource/create', [
    'json' => [
        'resourceId' => '123',
        'foo' => 'bar'
    ]
]);
```

### Dependency injection and testing

If you need to depend on Wonnova's Client object, always use the `Wonnova\SDK\Connection\ClientInterface` instead of the concrete `Client` object. This way you will be able to replace the object in case of need.

If you don't want to replace all the methods in the Client object but you need to test another object that depends on it and don't want real HTTP requests to be performed, there is no problem. The object `Wonnova\SDK\Connection\Client` is based and extends `GuzzleHttp\Client`, so you will be able to mock HTTP requests as explained [here](http://guzzle.readthedocs.org/en/latest/testing.html).

An example.

```php
// Create a client instance
$wonnovaClient = new \Wonnova\SDK\Connection\Client(
    new \Wonnova\SDK\Auth\Credentials(['key' => 'AaBbCcDd123456'])
);

// Create a mock subscriber
$mockSubscriber = new \GuzzleHttp\Subscriber\Mock([
    // Add a response that will mock the authentication request
    new \GuzzleHttp\Message\Response(
        200,
        [],
        new \GuzzleHttp\Stream\Stream(fopen('data://text/plain,{"token": "foobar"}', 'r'))
    ),
    
    // Add another response that will mock the request you want to test
    new \GuzzleHttp\Message\Response(
        200,
        [],
        new \GuzzleHttp\Stream\Stream(fopen('data://text/plain,...', 'r'))
    ),
]);

// Set the mock subscriber to the client instance
$wonnovaClient->getEmitter()->attach($mockSubscriber);
$wonnovaClient->getUsers(); // This won't perform a real HTTP request
```

You just need to set a response content that is compatible with what the SDK expectes to get from the request.

### Documentation

If you need to know the specifications of the API that is consumed by this SDK, or want to get an extended documentation of the SDK itself, feel free to [contact us](http://www.wonnova.com/contacto).
