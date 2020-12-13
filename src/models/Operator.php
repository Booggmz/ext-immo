<?php

namespace booggmz\immo\models;

use booggmz\immo\models\query\OperatorQuery;
use booggmz\immo\models\query\QueueQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "operator".
 *
 * @property int         $id
 * @property string      $name
 * @property string|null $status
 *
 * @property Queue[]     $surveyQueues
 */
class Operator extends ActiveRecord
{
    public const STATUS_BUSY = 'busy',
        STATUS_OFFLINE = 'offline',
        STATUS_READY = 'ready';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'operator';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['status'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id'     => 'ID',
            'name'   => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[SurveyQueues]].
     *
     * @return \yii\db\ActiveQuery|QueueQuery
     */
    public function getSurveyQueues(): QueueQuery
    {
        return $this->hasMany(Queue::class, ['operator_id' => 'id']);
    }

    /**
     * @return OperatorQuery
     */
    public static function find(): OperatorQuery
    {
        return new OperatorQuery(get_called_class());
    }

    /**
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }
}
