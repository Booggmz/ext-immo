<?php

namespace Booggmz\Immo\models;

use Booggmz\Immo\models\query\QueueQuery;
use Booggmz\Immo\models\query\ServiceQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "service".
 *
 * @property int     $id
 * @property string  $title
 *
 * @property Queue[] $surveyQueues
 */
class Service extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'service';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id'    => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[SurveyQueues]].
     *
     * @return \yii\db\ActiveQuery|QueueQuery
     */
    public function getSurveyQueues(): QueueQuery
    {
        return $this->hasMany(Queue::class, ['service_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ServiceQuery the active query used by this AR class.
     */
    public static function find(): ServiceQuery
    {
        return new ServiceQuery(get_called_class());
    }
}
