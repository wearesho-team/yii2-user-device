<?php

namespace Wearesho\Yii\UserDevice\Tests\Unit;

use Wearesho\Yii\UserDevice\Record;
use Wearesho\Yii\UserDevice\Tests\TestCase;
use yii\db\BaseActiveRecord;

/**
 * Class RecordTest
 * @package Wearesho\Yii\UserDevice\Tests\Unit
 * @coversDefaultClass \Wearesho\Yii\UserDevice\Record
 * @internal
 */
class RecordTest extends TestCase
{
    /** @var Record */
    protected $record;

    protected function setUp()
    {
        parent::setUp();

        $this->record = new Record();
    }

    public function testValidateEmptyRecord(): void
    {
        $this->assertFalse($this->record->validate());
        $this->assertArrayHasKey('user_id', $this->record->errors);
        $this->assertArrayHasKey('user_agent', $this->record->errors);
        $this->assertArrayHasKey('ip', $this->record->errors);
    }

    public function testValidateUserId(): void
    {
        $this->assertFalse($this->record->validate('user_id'));

        $this->record->user_id = mt_rand(1, 100);

        $this->assertTrue($this->record->validate('user_id'));
    }

    public function testValidateUserAgent(): void
    {
        $this->assertFalse($this->record->validate('user_agent'));

        $this->record->user_agent = 'test_user_agent';

        $this->assertTrue($this->record->validate('user_agent'));
    }

    public function testValidateIp(): void
    {
        $this->assertFalse($this->record->validate('ip'));

        $this->record->ip = '3ffe:1900:4545:3:200:f8ff:fe21:67cf'; // fake ipv4

        $this->assertTrue($this->record->validate('ip'));
    }

    public function testBehaviors(): void
    {
        $this->record = new Record([
            'user_agent' => 'test_user_agent',
            'user_id' => mt_rand(1, 100),
            'ip' => '3ffe:1900:4545:3:200:f8ff:fe21:67cf'
        ]);
        $this->record->trigger(BaseActiveRecord::EVENT_BEFORE_INSERT);
        $this->assertNotEmpty($this->record->created_at);
        $this->assertNotEmpty($this->record->updated_at);

        $this->record->save();
    }
}
