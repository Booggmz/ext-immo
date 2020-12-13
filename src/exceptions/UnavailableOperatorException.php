<?php

namespace Booggmz\Immo\exceptions;

use yii\base\Exception;

class UnavailableOperatorException extends Exception
{
    protected $message = 'Operator is offline!';
}