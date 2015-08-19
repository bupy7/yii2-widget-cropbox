<?php

namespace bupy7\cropbox;

use yii\web\AssetBundle;

/**
 * Core assets of widget.
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 4.0.0
 */
class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@bupy7/cropbox/assets';
    public $css = [
        'style.css',
    ];
    public $depends = [
        'bupy7\cropbox\CropboxAsset',
    ];
}

