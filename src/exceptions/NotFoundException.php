<?php

namespace booggmz\immo\exceptions;

use yii\db\Exception;

class NotFoundException extends Exception
{
    protected $message = 'Not found';
}