<?php

namespace Booggmz\Immo\models\query;

use Booggmz\Immo\models\Operator;

/**
 * This is the ActiveQuery class for [[Operator]].
 *
 * @see Operator
 * @method Operator one($db = NULL)
 * @method Operator[]|array all($db = NULL)
 */
class OperatorQuery extends BaseQuery
{
    /**
     * @return $this
     */
    public function onlyAvailable(): self
    {
        return $this->andWhere([
            Operator::tableName() . '.status' => Operator::STATUS_READY,
        ]);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function byName(string $name): self
    {
        return $this->andWhere([
            Operator::tableName() . '.name' => $name,
        ]);
    }
}
