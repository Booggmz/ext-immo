<?php

namespace Booggmz\Immo\exceptions;

use yii\base\Exception;

class NoAvailableOperatorsException extends Exception
{
    protected $message = 'No available operators, please try latter';
}