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
    public $css = [
        'cropbox.css',
    ];
    public $js = [
        'cropbox.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'bupy7\cropbox\assets\CropboxAsset',
    ];
}

