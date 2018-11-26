<?php

namespace Wearesho\Yii\UserDevice\Tests\Unit;

use Wearesho\Yii\UserDevice;
use yii\caching\ArrayCache;
use yii\caching\Cache;
use yii\caching\DbCache;
use yii\rbac\PhpManager;
use yii\web\Application;
use yii\web\User;

/**
 * Class BootstrapTest
 * @package Wearesho\Yii\UserDevice\Tests\Unit
 */
class BootstrapTest extends UserDevice\Tests\TestCase
{
    protected $aliases;

    protected function setUp()
    {
        parent::setUp();

        $this->aliases = \Yii::$aliases;
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Yii::$aliases = $this->aliases;
    }

    public function testBootstrap(): void
    {
        $bootstrap = new UserDevice\Bootstrap();
        \Yii::$container->set('cache', ArrayCache::class);
        $bootstrap->bootstrap($this->app);
        $this->assertEquals(
            \Yii::getAlias('@vendor/wearesho-team/yii2-user-device/src'),
            \Yii::getAlias('@Wearesho/Yii/UserDevice')
        );
    }
}
