<?php

namespace Wearesho\Yii\UserDevice;

use yii\base;
use yii\console;
use yii\web;
use Horat1us\Yii\Traits\BootstrapMigrations;

/**
 * Class Bootstrap
 * @package Wearesho\Yii\UserDevice
 */
class Bootstrap extends base\BaseObject implements base\BootstrapInterface
{
    use BootstrapMigrations;

    /**
     * @param base\Application $app
     */
    public function bootstrap($app)
    {
        \Yii::setAlias('Wearesho/Yii/UserDevice', '@vendor/wearesho-team/yii2-user-device/src');

        switch (get_class($app)) {
            case console\Application::class:
                /** @noinspection PhpParamsInspection */
                $this->appendMigrations($app, 'Wearesho\\Yii\\UserDevice\\Migrations');
                return;
            case web\Application::class:
                $app->attachBehavior('user-device', Behavior::class);
        }
    }
}
