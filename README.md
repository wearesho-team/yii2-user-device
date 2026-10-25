# Yii2 User Device
[![Test & Lint](https://github.com/wearesho-team/yii2-user-device/actions/workflows/php.yml/badge.svg?branch=master)](https://github.com/wearesho-team/yii2-user-device/actions/workflows/php.yml)

Library for storing users user agent and ip info into database.

Compatibility: tested on PHP 8.4, PHP 8.5 (PostgreSQL and MySQL)

## Installation
```bash
composer require wearesho-team/yii2-user-device:^1.3
```

## Usage
1. Append [Bootstrap](./src/Bootstrap.php) to your application
```php
<?php

use Wearesho\Yii\UserDevice;

return [
    'bootstrap' => [
         'user-device' => UserDevice\Bootstrap::class,
    ],
];
```
2. Create migration to relate *user* and *user_device* tables
3. Use [UserDevice\Record](./src/Record.php) to find users device info

## License
[MIT](./LICENSE)
