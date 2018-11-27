<?php

namespace Wearesho\Yii\UserDevice\Tests\Unit;

use Wearesho\Yii\UserDevice;
use yii\caching\ArrayCache;
use yii\phpunit\TestLogger;
use yii\web;

/**
 * Class BehaviorTest
 * @package Wearesho\Yii\UserDevice\Tests\Unit
 */
class BehaviorTest extends UserDevice\Tests\TestCase
{
    protected const IP = '3ffe:1900:4545:3:200:f8ff:fe21:67cf';
    protected const AGENT = 'agent';

    /** @var TestLogger */
    protected $testLogger;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        \Yii::$container->set('cache', ArrayCache::class);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->testLogger = new class extends TestLogger
        {
            /** @var array */
            public $log;

            public function log($message, $level, $category = 'application')
            {
                $this->log[] = [$message, $level, $category];
            }
        };
        \Yii::setLogger($this->testLogger);
    }

    public function testSuccessBehavior(): void
    {
        \Yii::$app->request->headers->set('User-Agent', static::AGENT);
        \Yii::$app->request->headers->set('X-Forwarded-For', static::IP);
        $this->createUser()->trigger(web\Application::EVENT_AFTER_REQUEST);

        $record = UserDevice\Record::find()->where(['=', 'ip', static::IP])->one();
        $this->assertNotNull($record);
        $this->assertEquals(static::AGENT, $record->user_agent);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(1, $record->delete());
    }

    public function testEmptyUserId(): void
    {
        $user = new class extends web\User implements web\IdentityInterface
        {
            public $identityClass = web\User::class;

            public static function findIdentity($id)
            {
                return $id;
            }

            /**
             * @param mixed $token
             * @param null $type
             *
             * @return void|web\IdentityInterface
             * @throws \Exception
             */
            public static function findIdentityByAccessToken($token, $type = null)
            {
                throw new \Exception('Method not implemented!');
            }

            public function getId(): ?int
            {
                return null;
            }

            /**
             * @return string|void
             * @throws \Exception
             */
            public function getAuthKey()
            {
                throw new \Exception('Method not implemented!');
            }

            /**
             * @param string $authKey
             *
             * @return bool|void
             * @throws \Exception
             */
            public function validateAuthKey($authKey)
            {
                throw new \Exception('Method not implemented!');
            }
        };
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->loginAs($user);
        $behavior = $this->createBehavior($user);
        $behavior->storeUserDevice();

        $this->assertNull($user->id);
        $this->assertArraySubset(
            [
                [
                    "User '' logged in from . Session not enabled.",
                    4,
                    "yii\web\User::login",
                ],
                [
                    "Skipping saving user device for guest",
                    8,
                    "Wearesho\Yii\UserDevice\Behavior"
                ]
            ],
            $this->testLogger->log
        );
    }

    public function testEmptyUserAgent(): void
    {
        $user = $this->createUser();
        $behavior = $this->createBehavior($user);

        $behavior->storeUserDevice();

        $this->assertRegExp("/User '[0-9]+' logged in from . Session not enabled./", $this->testLogger->log[0][0]);
        $this->assertEquals(4, $this->testLogger->log[0][1]);
        $this->assertEquals("yii\web\User::login", $this->testLogger->log[0][2]);
        $this->assertRegExp("/Missing user agent header in request for user [0-9]+/", $this->testLogger->log[1][0]);
        $this->assertEquals(8, $this->testLogger->log[1][1]);
        $this->assertEquals("Wearesho\Yii\UserDevice\Behavior", $this->testLogger->log[1][2]);
    }

    protected function createBehavior($user): UserDevice\Behavior
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var UserDevice\Behavior $behavior */
        $behavior = \Yii::$container->get(UserDevice\Behavior::class, [
            'user' => $user,
        ]);

        return $behavior;
    }

    protected function createUser(): UserDevice\Tests\Mocks\UserMock
    {
        $user = new UserDevice\Tests\Mocks\UserMock(mt_rand());
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->loginAs($user);

        return $user;
    }
}
