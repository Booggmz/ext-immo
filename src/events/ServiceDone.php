<?php

namespace Booggmz\Immo\events;

use Booggmz\Immo\models\Queue;
use yii\base\Event;

class ServiceDone extends Event
{
    public const NAME = 'queueItemDone';

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