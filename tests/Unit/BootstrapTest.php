<?php

namespace Wearesho\Yii\UserDevice\Tests\Unit;

use Wearesho\Yii\UserDevice;
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

    public function testBootstrapWebApp(): void
    {
        $bootstrap = new UserDevice\Bootstrap();
        $bootstrap->bootstrap($this->app);
        $this->assertEquals(
            \Yii::getAlias('@vendor/wearesho-team/yii2-user-device/src'),
            \Yii::getAlias('@Wearesho/Yii/UserDevice')
        );
    }

    public function testBootstrapConsoleWeb(): void
    {
        $bootstrap = new UserDevice\Bootstrap();
        /** @noinspection PhpUnhandledExceptionInspection */
        $bootstrap->bootstrap(new \yii\console\Application([
            'id' => 'yii2-user-device',
            'basePath' => dirname(__DIR__),
            'components' => [
                'user' => [
                    'class' => User::class,
                    'identityClass' => UserDevice\Tests\Mocks\UserMock::class,
                    'enableSession' => false,
                ],
                'request' => [
                    'cookieValidationKey' => 'test',
                ],
            ],
        ]));
        $this->assertEquals(
            \Yii::getAlias('@vendor/wearesho-team/yii2-user-device/src'),
            \Yii::getAlias('@Wearesho/Yii/UserDevice')
        );
    }
}
