<?php

namespace Wearesho\Yii\UserDevice;

use yii\base;
use yii\console;
use yii\web;

class Bootstrap implements base\BootstrapInterface
{
    public function bootstrap($app): void
    {
        if ($app instanceof console\Application) {
            $bootstrap = new Migrations\Bootstrap;
            $bootstrap->bootstrap($app);
            return;
        }
        $app->attachBehavior('user-device', Behavior::class);
    }
}
