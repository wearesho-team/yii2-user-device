<?php

namespace Wearesho\Yii\UserDevice\Tests\Unit;

use Wearesho\Yii\UserDevice\Record;
use Wearesho\Yii\UserDevice\Tests\TestCase;

/**
 * Class RecordTest
 * @package Wearesho\Yii\UserDevice\Tests\Unit
 */
class RecordTest extends TestCase
{
    public function fixtures(): array
    {
        return [
            Record::class,
        ];
    }

    public function testFind(): void
    {
        $userDevice = Record::find()->one();

        $this->assertInstanceOf(Record::class, $userDevice);
        $this->seeRecord($userDevice, [
            'id' => $userDevice->id,
        ]);
    }
}
