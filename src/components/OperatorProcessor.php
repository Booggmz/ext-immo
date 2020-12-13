<?php

namespace Booggmz\Immo\components;

use Booggmz\Immo\events\ServiceDone;
use Booggmz\Immo\events\ServiceQueued;
use Booggmz\Immo\exceptions\UnavailableOperatorException;
use Booggmz\Immo\models\Operator;
use yii\base\Component;

class OperatorProcessor extends Component
{
    /**
     * @param ServiceQueued $event
     *
     * @throws UnavailableOperatorException
     */
    public function onServiceQueued(ServiceQueued $event): void
    {
        if ($event->getModel()->operator->status === Operator::STATUS_OFFLINE) {
            throw new UnavailableOperatorException();
        }

        if ($event->getModel()->operator->status === Operator::STATUS_BUSY) {
            \Yii::warning(
                "Added service {$event->getModel()->service_id} to busy operator: {$event->getModel()->operator_id}",
                self::class
            );

            return;
        }

        \Yii::info("Setting operator {$event->getModel()->operator_id} to busy status");

        $event->getModel()->operator->setStatus(Operator::STATUS_BUSY)->save();
    }

    /**
     * @param ServiceDone $event
     */
    public function onServiceDone(ServiceDone $event): void
    {
        if (!$event->getModel()->operator->getSurveyQueues()->onlyNotExecuted()->exists()) {
            \Yii::info("Setting operator {$event->getModel()->operator->id} status to ready", self::class);
            $event->getModel()->operator->setStatus(Operator::STATUS_READY)->save();
        }
    }

}