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
}
