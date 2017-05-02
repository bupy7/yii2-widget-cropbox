<?php

namespace bupy7\cropbox;

use yii\web\AssetBundle;

/**
 * @author Vasilij Belosludcev <bupy765@gmail.com>
 * @since 1.0.0
 */
class CropboxAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@bower/js-cropbox/build';
        $this->css = ['cropbox' . (!YII_DEBUG ? '.min' : '') . '.css'];
        $this->js = ['cropbox' . (!YII_DEBUG ? '.min' : '') . '.js'];
        parent::init();
    }
}
