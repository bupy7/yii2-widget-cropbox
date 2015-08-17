<?php

namespace bupy7\cropbox;

use yii\web\AssetBundle;

/**
 * Assets of jQuery plugin 'cropbox'.
 * @author Vasilij Belosludcev <bupy765@gmail.com>
 * @since 1.0.0
 */
class CropboxAsset extends AssetBundle
{
    public $sourcePath = '@bupy7/cropbox/assets';
    public $css = [
        'jquery.cropbox.css',
    ];
    public $js = [
        'jquery.cropbox.js',
    ];
    public $depends = [
        'bupy7\cropbox\MouseWheelAsset',
    ];
}
