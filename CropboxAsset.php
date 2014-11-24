<?php
namespace bupy7\cropbox;

use Yii;
use yii\web\AssetBundle;

class CropboxAsset extends AssetBundle
{
    
    public $sourcePath = '@bupy7/cropbox/assets';
    public $css = [
        'style.css',
    ];
    public $js = [
        'cropbox.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
}

