<?php

namespace bupy7\cropbox\tests\functionals;

use Yii;

class CropboxTest extends TestCase
{
    public function testAssets()
    {
        $this->mockApplication();
        $result = Yii::$app->runAction('cropbox/index');
        $this->assertNotEmpty($result);
    }
}
