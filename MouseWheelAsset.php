<?php

namespace bupy7\cropbox;

use yii\web\AssetBundle;

/**
 * Assets of jQuery plugin 'jquery-mousewheel'.
 * 
 * HomePage: {@link https://github.com/jquery/jquery-mousewheel}.
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 4.0.0
 */
class MouseWheelAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-mousewheel';
    public $js = [
        'jquery.mousewheel.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
