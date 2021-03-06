<?php

namespace Booggmz\Immo\components;

use Booggmz\Immo\events\ServiceDone;
use Booggmz\Immo\events\ServiceQueued;
use Booggmz\Immo\exceptions\NoAvailableOperatorsException;
use Booggmz\Immo\exceptions\NotFoundException;
use Booggmz\Immo\models\Operator;
use Booggmz\Immo\models\Queue;
use Booggmz\Immo\models\Service;
use yii\base\Component;
use yii\db\Expression;

class QueueProcessor extends Component
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->on(ServiceQueued::NAME, [OperatorProcessor::class, 'onServiceQueued']);
        $this->on(ServiceDone::NAME, [OperatorProcessor::class, 'onServiceDone']);
        parent::init();
    }

    /**
     * @param Service       $service
     * @param Operator|null $operator
     *
     * @throws NoAvailableOperatorsException
     * @throws NotFoundException
     */
    public function queueService(Service $service, Operator $operator = NULL): void
    {
        $dbTransaction = Queue::getDb()->beginTransaction();
        try {
            $queue             = new Queue();
            $queue->created_at = new Expression('NOW()');
            $queue->service_id = $service->id;

            if (!$operator) {
                try {
                    $operator = Operator::find()
                        ->onlyAvailable()
                        ->orderBy('RAND()')
                        ->strict()
                        ->one();
                } catch (NotFoundException $e) {
                    throw new NoAvailableOperatorsException();
                }
            }

            $queue->link(Queue::R_OPERATOR, $operator);
            $queue->link(Queue::R_SERVICE, $service);

            if ($queue->save()) {
                $this->trigger(ServiceQueued::NAME, new ServiceQueued($queue));
            }

            $dbTransaction->commit();
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param Queue $queue
     *
     * @throws \yii\db\Exception
     */
    public function markAsDone(Queue $queue): void
    {
        $dbTransaction = Queue::getDb()->beginTransaction();
        try {
            $queue->markExecuted();
            if ($queue->save()) {
                $this->trigger(ServiceDone::NAME, new ServiceDone($queue));
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            throw $e;
        }
    }
}