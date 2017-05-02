<?php

namespace bupy7\cropbox;

use yii\web\AssetBundle;

/**
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 4.0.0
 */
class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@bupy7/cropbox/assets';
    public $css = [
        'cropbox.css',
    ];
    public $js = [
        'cropbox.css',
    ];
    public $depends = [
        'bupy7\cropbox\CropboxAsset',
    ];
}

