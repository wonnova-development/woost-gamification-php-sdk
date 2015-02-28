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
            $this->assertEquals($progressData[$key]['completed'], $questStep->isCompleted());
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

    public function testGetLevels()
    {
        $levelsData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getLevels.json'), true);
        $levelsData = $levelsData['levels'];
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getLevels.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $levels = $this->client->getLevels();
        $this->assertCount(2, $levels);
        foreach ($levels as $key => $level) {
            $this->assertEquals($levelsData[$key]['id'], $level->getId());
            $this->assertEquals($levelsData[$key]['code'], $level->getCode());
            $this->assertEquals($levelsData[$key]['name'], $level->getName());
            $this->assertEquals($levelsData[$key]['score'], $level->getScore());
            $this->assertEquals($levelsData[$key]['generatesNotification'], $level->getGeneratesNotification());
            $this->assertEquals($levelsData[$key]['categoryEnabled'], $level->isCategoryEnabled());
            $this->assertEquals($levelsData[$key]['imageUrl'], $level->getImageUrl());
            $this->assertInstanceOf('DateTime', $level->getDateCreated());
            if (! is_null($level->getBadge())) {
                $this->assertInstanceOf('Wonnova\SDK\Model\Badge', $level->getBadge());
            }
            if (! is_null($level->getScenario())) {
                $this->assertInstanceOf('Wonnova\SDK\Model\Scenario', $level->getScenario());
            }
        }
    }

    public function testGetUserLevelInScenario()
    {
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getUserLevelInScenario.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $level = $this->client->getUserLevelInScenario('', '');
        $this->assertEquals(33, $level->getId());
        $this->assertEquals('LEARNER', $level->getCode());
        $this->assertEquals(true, $level->getGeneratesNotification());
        $this->assertEquals('default.png', $level->getImageUrl());
        $this->assertInstanceOf('DateTime', $level->getDateCreated());
        $this->assertInstanceOf('Wonnova\SDK\Model\Badge', $level->getBadge());
        $this->assertEquals('The badge', $level->getBadge()->getName());
        $this->assertInstanceOf('Wonnova\SDK\Model\Scenario', $level->getScenario());
        $this->assertEquals('VCM', $level->getScenario()->getName());
    }

    public function testGetTeamsLeaderboard()
    {
        $teamsData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getTeamsLeaderboard.json'), true);
        $teamsData = $teamsData['scores'];
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getTeamsLeaderboard.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $teams = $this->client->getTeamsLeaderboard();
        $this->assertCount(4, $teams);
        foreach ($teams as $key => $team) {
            $this->assertEquals($teamsData[$key]['teamName'], $team->getTeamName());
            $this->assertEquals($teamsData[$key]['avatar'], $team->getAvatar());
            $this->assertEquals($teamsData[$key]['description'], $team->getDescription());
            $this->assertEquals($teamsData[$key]['position'], $team->getPosition());
            $this->assertEquals($teamsData[$key]['score'], $team->getScore());
        }
    }

    public function testGetItemsLeaderboard()
    {
        $itemsData = json_decode(file_get_contents(__DIR__ . '/../dummy_response_data/getItemsLeaderboard.json'), true);
        $itemsData = $itemsData['leaderboard'];
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/getItemsLeaderboard.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $items = $this->client->getItemsLeaderboard();
        $this->assertCount(3, $items);
        foreach ($items as $key => $item) {
            $this->assertEquals($itemsData[$key]['id'], $item->getItemId());
            $this->assertEquals($itemsData[$key]['title'], $item->getTitle());
            $this->assertEquals($itemsData[$key]['description'], $item->getDescription());
            $this->assertEquals($itemsData[$key]['author'], $item->getAuthor());
            $this->assertInstanceOf('DateTime', $item->getDateCreated());
        }
    }

    public function testRateItem()
    {
        // Set mocked response
        $body = new Stream(fopen(__DIR__ . '/../dummy_response_data/rateItem.json', 'r'));
        $this->subscriber->addResponse(new Response(200, [], $body));

        $item = $this->client->rateItem('', '');
        $this->assertEquals('3333', $item->getItemId());
        $this->assertEquals('the title', $item->getTitle());
        $this->assertNull($item->getDescription());
        $this->assertNull($item->getAuthor());
        $this->assertEquals(2400, $item->getScore());
        $this->assertInstanceOf('DateTime', $item->getDateCreated());
    }
}
