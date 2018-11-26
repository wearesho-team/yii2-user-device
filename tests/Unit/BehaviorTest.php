<?php

namespace Wearesho\Yii\UserDevice\Tests\Unit;

use Wearesho\Yii\UserDevice\Behavior;
use Wearesho\Yii\UserDevice\Tests\Mocks\UserMock;
use Wearesho\Yii\UserDevice\Tests\TestCase;
use yii\caching\ArrayCache;
use yii\caching\DbCache;
use yii\caching\FileCache;
use yii\web\Request;

/**
 * Class BehaviorTest
 * @package Wearesho\Yii\UserDevice\Tests\Unit
 */
class BehaviorTest extends TestCase
{
    public function testBehavior(): void
    {
        \Yii::$container->set('cache', ArrayCache::class);

        $mock = $this->getMockBuilder(Behavior::class)
            ->setMethods(['storeUserDevice'])
            ->getMock();
        $mock->expects($this->once())
            ->method('storeUserDevice');

        $user = new UserMock(mt_rand());
        $this->loginAs($user);

        \Yii::$app->request->headers->set('USER_AGENT', 'test_user_agent');
        /** @var Behavior $behavior */
        $behavior = \Yii::$container->get(Behavior::class, [
            'user' => $user,
        ]);

        $behavior->storeUserDevice();
    }
}
