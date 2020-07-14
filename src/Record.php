<?php

namespace Wearesho\Yii\UserDevice;

use Horat1us\Yii\CarbonBehavior;
use yii\db;

/**
 * @property string $id [integer]
 * @property string $user_id [integer]
 * @property string $user_agent
 * @property string $ip [varchar(39)]
 * @property int $created_at [timestamp(0)]
 * @property int $updated_at [timestamp(0)]
 */
class Record extends db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'user_device';
    }

    public function behaviors(): array
    {
        return [
            'ts' => [
                'class' => CarbonBehavior::class,
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['user_id', 'user_agent', 'ip',], 'required',],
            [['user_id',], 'integer', 'min' => 1,],
            [['user_agent',], 'string',],
            [['ip',], 'ip',],
            [
                ['user_id',],
                'unique',
                'filter' => function (db\ActiveQuery $query): db\ActiveQuery {
                    return $query
                        ->andWhere(['=', 'user_agent', $this->user_agent,])
                        ->andWhere(['=', 'ip', $this->ip,]);
                },
                'skipOnEmpty' => true,
                'skipOnError' => true,
            ],
        ];
    }
}
