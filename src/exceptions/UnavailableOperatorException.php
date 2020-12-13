<?php

namespace booggmz\immo\exceptions;

use yii\base\Exception;

class UnavailableOperatorException extends Exception
{
    protected $message = 'Operator is offline!';
}