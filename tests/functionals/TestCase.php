<?php

namespace bupy7\cropbox\tests\functionals;

use PHPUnit_Framework_TestCase;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Application;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        $this->destroyApplication();
    }

    /**
     * @param array $config
     */
    protected function mockApplication($config = [])
    {
        new Application(ArrayHelper::merge(require __DIR__ . '/assets/config/main.php', $config));
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
        Yii::$container = new Container;
    }
}
