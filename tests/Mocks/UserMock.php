<?php

namespace Wearesho\Yii\UserDevice\Tests\Mocks;

use Wearesho\Yii\UserDevice\Behavior;
use yii\web;

/**
 * Class UserMock
 * @package Wearesho\Yii\UserDevice\Tests\Mocks
 */
class UserMock extends web\User implements web\IdentityInterface
{
    public $identityClass = web\User::class;

    public $id;

    public function __construct(int $id, array $config = [])
    {
        parent::__construct($config);
        $this->id = $id;
    }

    public function behaviors()
    {
        return [
            'store-device' => [
                'class' => Behavior::class,
                'user' => $this,
                'request' => \Yii::$app->request,
            ],
        ];
    }

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

    public function getId(): int
    {
        return $this->id;
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
}
