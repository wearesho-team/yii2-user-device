<?php

namespace Wearesho\Yii\UserDevice\Tests;

use yii\helpers\ArrayHelper;
use yii\phpunit\MigrateFixture;

/**
 * Class TestCase
 * @package Wearesho\Yii\UserDevice\Tests
 */
class TestCase extends \yii\phpunit\TestCase
{
    public function globalFixtures(): array
    {
        $fixtures = [
            [
                'class' => MigrateFixture::class,
                'migrationNamespaces' => [
                    'Wearesho\\Yii\\UserDevice\\Migrations',
                ],
            ]
        ];

        return ArrayHelper::merge(parent::globalFixtures(), $fixtures);
    }
}
