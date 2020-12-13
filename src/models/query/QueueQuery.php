<?php

namespace Booggmz\Immo\models\query;

use Booggmz\Immo\models\Queue;

/**
 * This is the ActiveQuery class for [[SurveyQueue]].
 *
 * @see Queue
 * @method Queue|array|null one($db = NULL)
 * @method Queue[]|array all($db = NULL)
 */
class QueueQuery extends BaseQuery
{
    /**
     * @return $this
     */
    public function onlyNotExecuted(): self
    {
        return $this->andWhere([Queue::tableName() . '.executed_at' => NULL]);
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function byId(int $id): self
    {
        return $this->andWhere([Queue::tableName() . '.id' => $id]);
    }
}
