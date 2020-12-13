<?php

namespace Booggmz\Immo\commands;

use Booggmz\Immo\components\QueueProcessor;
use Booggmz\Immo\exceptions\NoAvailableOperatorsException;
use Booggmz\Immo\exceptions\NotFoundException;
use Booggmz\Immo\models\Operator;
use Booggmz\Immo\models\Queue;
use Booggmz\Immo\models\Service;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Yii;
use yii\console\Controller;

/**
 * Immo test task testing controller
 *
 * @package Booggmz\Immo\commands
 */
class TestingController extends Controller
{
    /** @var QueueProcessor */
    private $queueProcessor;

    /**
     * TestingController constructor.
     *
     * @param       $id
     * @param       $module
     * @param array $config
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($id, $module, $config = [])
    {
        $this->queueProcessor = Yii::$app->get('queueProcessor');
        parent::__construct($id, $module, $config);
    }

    /**
     * Show free operators list
     */
    public function actionShowAvailableOperators(): void
    {
        $operators = Operator::find()->onlyAvailable()->all();
        $table     = new Table(new ConsoleOutput());

        $table->setHeaders(['name', 'status']);
        foreach ($operators as $operator) {
            $table->addRow(['name' => $operator->name, 'status' => $operator->status]);
        }

        $table->render();
    }

    /**
     * Show queued services
     */
    public function actionShowQueue(): void
    {
        $queueItems = Queue::find()->onlyNotExecuted()->all();
        $table      = new Table(new ConsoleOutput());
        $table->setHeaders(['id', 'service', 'operator', 'created at']);
        foreach ($queueItems as $queueItem) {
            $table->addRow([
                'id'         => $queueItem->id,
                'service'    => $queueItem->service->title,
                'operator'   => $queueItem->operator->name,
                'created at' => $queueItem->created_at,
            ]);
        }

        $table->render();
    }

    /**
     * Mark queued service as executed
     *
     * @param int $queuedServiceId
     *
     * @throws NotFoundException
     * @throws \yii\db\Exception
     */
    public function actionExecuteService(int $queuedServiceId): void
    {
        $queue = Queue::find()->byId($queuedServiceId)->strict()->one();
        $this->queueProcessor->markAsDone($queue);
        echo "well done" . PHP_EOL;
    }

    /**
     * Add random service to random operator
     *
     * @param string|null $operatorName concrete operator name
     *
     * @throws NoAvailableOperatorsException
     * @throws NotFoundException
     */
    public function actionAddService(string $operatorName = NULL): void
    {
        $model    = Service::find()->orderBy('RAND()')->one();
        $operator = NULL;

        if ($operatorName) {
            $operator = Operator::find()->byName($operatorName)->strict()->one();
        }

        $this->queueProcessor->queueService($model, $operator);
        echo "Service $model->title queued" . PHP_EOL;
    }
}