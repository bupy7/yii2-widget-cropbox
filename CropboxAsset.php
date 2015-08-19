<?php

namespace bupy7\cropbox;

use yii\web\AssetBundle;

/**
 * Assets of jQuery plugin 'jq-cropbox'.
 * 
 * HomePage: {@link https://github.com/bupy7/jquery-cropbox}.
 * 
 * @author Vasilij Belosludcev <bupy765@gmail.com>
 * @since 1.0.0
 */
class CropboxAsset extends AssetBundle
{
    public $depends = [
        'bupy7\cropbox\MouseWheelAsset',
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@bower/jq-cropbox/' . (!YII_DEBUG ? 'dist' : 'src');
        $this->css = ['jquery.cropbox' . (!YII_DEBUG ? '.min' : '') . '.css'];
        $this->js = ['jquery.cropbox' . (!YII_DEBUG ? '.min' : '') . '.js'];
        parent::init();
    }
}
