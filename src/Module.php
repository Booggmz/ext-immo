<?php

namespace booggmz\immo;

/**
 * Class Module
 *
 * @package booggmz\immo
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'booggmz\immo\commands';

    public function init()
    {
        $this->controllerNamespace = 'booggmz\immo\commands';
        parent::init();
    }
}