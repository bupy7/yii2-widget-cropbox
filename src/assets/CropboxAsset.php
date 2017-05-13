<?php

namespace bupy7\cropbox\assets;

use yii\web\AssetBundle;

/**
 * @author Vasilij Belosludcev <bupy765@gmail.com>
 * @since 5.0.0
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
