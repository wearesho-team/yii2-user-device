# Yii2 User Device
Library for storing users user agent and ip info into database.

## Installation
```bash
composer require wearesho-team/yii2-user-device
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
