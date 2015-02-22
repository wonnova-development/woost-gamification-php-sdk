<?php
namespace Wonnova\SDK\Test\Connection;

use Doctrine\Common\Cache\ArrayCache;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Auth\Credentials;
use Wonnova\SDK\Connection\Client;
use Wonnova\SDK\Model\Achievement;
use Wonnova\SDK\Model\User;

class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Mock
     */
    private $subscriber;

    public function setUp()
    {
        $this->client = new Client(new Credentials(''), 'es', new ArrayCache());

        $this->subscriber = new Mock([
            // Add the auth token response that will be requested before every test
            new Response(200, [], new Stream(fopen('data://text/plain,{"token": "foobar"}', 'r')))
        ]);
        $this->client->getEmitter()->attach($this->subscriber);
    }

    public function testGetUser()
    {
        $userData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getUser.json'), true);
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUser.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $user = $this->client->getUser($userData['userId']);
        $this->assertInstanceOf('Wonnova\SDK\Model\User', $user);
        $this->assertEquals($userData['userId'], $user->getUserId());
        $this->assertEquals($userData['username'], $user->getUsername());
        $this->assertEquals($userData['provider'], $user->getProvider());
        $this->assertInstanceOf('DateTime', $user->getDateOfBirth());
        $this->assertEquals($userData['dateOfBirth']['date'], $user->getDateOfBirth()->format('Y-m-d H:i:s'));
        $this->assertNull($user->getTimezone());
    }

    public function testGetUsers()
    {
        $usersData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getUsers.json'), true);
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUsers.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $users = $this->client->getUsers();
        $this->assertCount(3, $users);
        foreach ($users as $key => $user) {
            $this->assertInstanceOf('Wonnova\SDK\Model\User', $user);
            $this->assertEquals($usersData[$key]['userId'], $user->getUserId());
            $this->assertEquals($usersData[$key]['username'], $user->getUsername());
            $this->assertEquals($usersData[$key]['provider'], $user->getProvider());
            $this->assertInstanceOf('DateTime', $user->getDateOfBirth());
            $this->assertEquals(
                $usersData[$key]['dateOfBirth']['date'],
                $user->getDateOfBirth()->format('Y-m-d H:i:s')
            );
        }
    }

    public function testCreateUser()
    {
        $expectedId = 'foobar123';
        // Set mocked response
        $body = new Stream(fopen(sprintf('data://text/plain,{"userId": "%s"}', $expectedId), 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $newUser = new User();
        $newUser->setEmail('foo@bar.com')
                ->setDateOfBirth(new \DateTime('1970-01-01 00:00:00'))
                ->setFullName('John Doe');
        $this->assertNull($newUser->getUserId());
        $this->client->createUser($newUser);
        $this->assertEquals($expectedId, $newUser->getUserId());
    }

    public function testUpdateUser()
    {
        $userData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getUser.json'), true);
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUser.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $user = new User();
        $user->setUserId('123')
             ->setEmail('new@email.com')
             ->setFullName('EDITED');
        $this->client->updateUser($user);

        // Test that the user has been populated with the response data
        $this->assertEquals($userData['userId'], $user->getUserId());
        $this->assertEquals($userData['username'], $user->getUsername());
        $this->assertEquals($userData['provider'], $user->getProvider());
        $this->assertInstanceOf('DateTime', $user->getDateOfBirth());
        $this->assertEquals($userData['dateOfBirth']['date'], $user->getDateOfBirth()->format('Y-m-d H:i:s'));
        $this->assertNull($user->getTimezone());
    }

    /**
     * @expectedException \Wonnova\SDK\Exception\InvalidArgumentException
     */
    public function testUpdateUserWithoutIdThrowsException()
    {
        $this->client->updateUser(new User());
    }

    public function testGetUserNotifications()
    {
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUserNotifications.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $notifications = $this->client->getUserNotifications('');
        $this->assertCount(3, $notifications);
        $this->assertEquals(Achievement::TYPE_POINTS, $notifications->get(0)->getType());
        $this->assertEquals('First pending notification', $notifications->get(0)->getMessage());
        $this->assertEquals(Achievement::TYPE_POINTS, $notifications->get(1)->getType());
        $this->assertEquals('Second pending notification', $notifications->get(1)->getMessage());
        $this->assertEquals(Achievement::TYPE_BADGE, $notifications->get(2)->getType());
        $this->assertEquals('Third pending notification', $notifications->get(2)->getMessage());
    }

    public function testGetUserBadges()
    {
        $badgesData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getUserBadges.json'), true);
        $badgesData = $badgesData['badges'];
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUserBadges.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $badges = $this->client->getUserBadges('');
        $this->assertCount(2, $badges);
        foreach ($badges as $key => $badge) {
            $this->assertEquals($badgesData[$key]['id'], $badge->getId());
            $this->assertEquals($badgesData[$key]['type'], $badge->getType());
            $this->assertInstanceOf('DateTime', $badge->getNotificationDate());
            $this->assertEquals($badgesData[$key]['imageUrl'], $badge->getImageUrl());
            $this->assertEquals($badgesData[$key]['name'], $badge->getName());
            $this->assertEquals($badgesData[$key]['description'], $badge->getDescription());
        }
    }

    public function testGetUserAchievements()
    {
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUserAchievements.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $achievements = $this->client->getUserAchievements('');
        $this->assertCount(2, $achievements);
        $this->assertEquals(Achievement::TYPE_POINTS, $achievements->get(0)->getType());
        $this->assertEquals(165, $achievements->get(0)->getValue());
        $this->assertEquals(Achievement::TYPE_BADGE, $achievements->get(1)->getType());
        $this->assertEquals(2, $achievements->get(1)->getValue());
    }

    public function testGetUserProgressInQuest()
    {
        $progressData = json_decode(
            file_get_contents(__DIR__ . '/../dummy_response_data/getUserProgressInQuest.json'),
            true
        );
        $progressData = $progressData['questSteps'];
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUserProgressInQuest.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $questSteps = $this->client->getUserProgressInQuest('', '');
        $this->assertCount(4, $questSteps);
        foreach ($questSteps as $key => $questStep) {
            $this->assertEquals($progressData[$key]['id'], $questStep->getId());
            $this->assertEquals($progressData[$key]['type'], $questStep->getType());
            $this->assertEquals($progressData[$key]['code'], $questStep->getCode());
            $this->assertEquals($progressData[$key]['name'], $questStep->getName());
            $this->assertEquals($progressData[$key]['description'], $questStep->getDescription());
            $this->assertEquals($progressData[$key]['completed'], $questStep->getCompleted());
        }
    }

    public function testGetQuests()
    {
        $questsData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getQuests.json'), true);
        $questsData = $questsData['quests'];
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getQuests.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $quests = $this->client->getQuests();
        $this->assertCount(3, $quests);
        foreach ($quests as $key => $quest) {
            $this->assertEquals($questsData[$key]['id'], $quest->getId());
            $this->assertInstanceof('DateTime', $quest->getStartDate());
            $this->assertEquals($questsData[$key]['code'], $quest->getCode());
            $this->assertEquals($questsData[$key]['generatesNotification'], $quest->getGeneratesNotification());
            $this->assertEquals($questsData[$key]['name'], $quest->getName());
            $this->assertEquals($questsData[$key]['description'], $quest->getDescription());
        }
    }
}
