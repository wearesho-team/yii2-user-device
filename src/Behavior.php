<?php

namespace Wearesho\Yii\UserDevice;

use Carbon\Carbon;
use yii\base;
use yii\web;
use yii\caching;
use yii\di;

/**
 * Class Behavior
 * @package Wearesho\Yii\UserDevice
 */
class Behavior extends base\Behavior
{
    protected const COOKIE_NAME = 'b.sd'; // bobra saved device

    /** @var string|array|web\User */
    public $user = 'user';

    /** @var string|array|web\Request */
    public $request = 'request';

    /** @var string|array|caching\Cache */
    public $cache = 'cache';

    public function events(): array
    {
        return [
            base\Application::EVENT_BEFORE_REQUEST => 'storeUserDevice',
        ];
    }

    /**
     * @throws base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        $this->user = di\Instance::ensure($this->user, web\User::class);
        $this->request = di\Instance::ensure($this->request, web\Request::class);
        $this->cache = di\Instance::ensure($this->cache, caching\Cache::class);
    }

    public function storeUserDevice(): void
    {
        $userId = $this->user->id;

        if (is_null($userId)) {
            \Yii::debug("Skipping saving user device for guest", static::class);
            return;
        }

        $userAgent = $this->request->userAgent;
        if (is_null($userAgent)) {
            \Yii::debug(
                "Missing user agent header in request for user {$userId}",
                static::class
            );
            return;
        }

        $ip = $this->request->userIP;
        if (is_null($ip)) {
            \Yii::debug(
                "Missing IP info for user {$userId}",
                static::class
            );
            return;
        }

        $shouldSkipUpdate = $this->cache->get(compact('userAgent', 'userId', 'ip'));
        if ($shouldSkipUpdate) {
            \Yii::debug("Skipping updating user info", static::class);
            return;
        };

        $this->create($userId, $userAgent, $ip);
        $this->cache->set(compact('userAgent', 'userId', 'ip'), 1, 60);
    }

    protected function create(int $userId, string $userAgent, string $ip): void
    {
        $record = new Record([
            'user_agent' => $userAgent,
            'user_id' => $userId,
            'ip' => $ip
        ]);

        if (!$record->save()) {
            // Unique Validator Failed
            $this->update($userId, $userAgent, $ip);
            return;
        }
        \Yii::debug("Created device info {$record->id} for user {$userId}", static::class);

        return;
    }

    protected function update(int $userId, string $userAgent, string $ip): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $count = Record::getDb()->createCommand()->update('user_device', [
            'updated_at' => Carbon::now()->toDateTimeString()
        ], [
            'user_id' => $userId,
            'user_agent' => $userAgent,
            'ip' => $ip
        ])->execute();

        if ($count !== 0) {
            \Yii::debug("Updated info for user {$userId}", static::class);
            return null;
        }

        $this->create($userId, $userAgent, $ip);
    }
}
