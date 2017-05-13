<?php

namespace bupy7\cropbox\assets;

use yii\web\AssetBundle;

/**
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 5.0.0
 */
class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@bupy7/cropbox/resources';
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'bupy7\cropbox\assets\CropboxAsset',
    ];

    /**
     * @since 5.0.1
     */
    public function init()
    {
        $this->css = ['cropbox' . (!YII_DEBUG ? '.min' : '') . '.css'];
        $this->js = ['cropbox' . (!YII_DEBUG ? '.min' : '') . '.js'];
        parent::init();
    }
}
