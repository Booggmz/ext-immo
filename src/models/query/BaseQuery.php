<?php

namespace Booggmz\Immo\models\query;

use Booggmz\Immo\exceptions\NotFoundException;
use yii\db\ActiveQuery;
use yii\db\Connection;

class BaseQuery extends ActiveQuery
{
    protected $strict = false;

    public function strict(): BaseQuery
    {
        $this->strict = true;

        return $this;
    }

    /**
     * @param Connection|null $db
     *
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundException
     */
    public function one($db = NULL)
    {
        $result = parent::one($db);
        if ($this->strict && !$result) {
            throw new NotFoundException('Not found');
        }

        return $result;
    }
}