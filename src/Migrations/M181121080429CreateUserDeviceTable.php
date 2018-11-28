<?php

namespace Wearesho\Yii\UserDevice\Migrations;

use yii\db\Migration;

/**
 * Class M181121080429CreateUserDeviceTable
 */
class M181121080429CreateUserDeviceTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('user_device', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'user_agent' => $this->text(256)->notNull(),
            'ip' => $this->string(39)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('now()'),
        ]);

        $this->createIndex('user_device_unique', 'user_device', [
            'user_id',
            'user_agent(256)',
            'ip',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('user_device');
    }
}
