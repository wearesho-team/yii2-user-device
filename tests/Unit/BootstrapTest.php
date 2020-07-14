<?php

namespace Wearesho\Yii\UserDevice\Tests\Unit;

use Wearesho\Yii\UserDevice;
use yii\web\User;

/**
 * Class BootstrapTest
 * @package Wearesho\Yii\UserDevice\Tests\Unit
 * @coversDefaultClass \Wearesho\Yii\UserDevice\Bootstrap
 * @internal
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
        $this->assertInstanceOf(
            UserDevice\Behavior::class,
            $this->app->getBehavior('user-device')
        );
    }

    public function testBootstrapConsole(): void
    {
        $bootstrap = new UserDevice\Bootstrap();
        /** @noinspection PhpUnhandledExceptionInspection */
        $bootstrap->bootstrap($app = new \yii\console\Application([
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
        $this->assertEquals('Wearesho\Yii\UserDevice\Migrations', $app->controllerMap['migrate']['migrationNamespaces'][0]);
    }
}
