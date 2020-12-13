<?php

namespace booggmz\immo\events;

use booggmz\immo\models\Queue;
use yii\base\Event;

class ServiceQueued extends Event
{
    public const NAME = 'queueItemPrepared';

    /** @var Queue */
    private $model;

    public function __construct($model, $config = [])
    {
        $this->model = $model;
        parent::__construct($config);
    }

    /**
     * @return Queue
     */
    public function getModel(): Queue
    {
        return $this->model;
    }

}