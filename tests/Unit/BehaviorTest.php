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
    protected $log;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        \Yii::$container->set('cache', ArrayCache::class);
    }

    protected function setUp()
    {
        parent::setUp();

        $logger = new class extends TestLogger {
            /** @var array */
            public $log;

            public function log($message, $level, $category = 'application')
            {
                $this->log[] = [$message, $level, $category];
            }
        };
        \Yii::setLogger($logger);
    }

    public function testSuccessBehavior(): void
    {
        $user = new UserDevice\Tests\Mocks\UserMock(mt_rand());
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->loginAs($user);

        /** @var UserDevice\Behavior $behavior */
        $behavior = $this->createBehavior($user);
        $behavior->request->headers->set('User-Agent', static::AGENT);
        $behavior->request->headers->set('X-Forwarded-For', static::IP);

        $behavior->storeUserDevice();

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

        /** @var UserDevice\Behavior $behavior */
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
            \Yii::getLogger()->log
        );
    }

    protected function createBehavior($user)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return \Yii::$container->get(UserDevice\Behavior::class, [
            'user' => $user,
        ]);
    }
}
