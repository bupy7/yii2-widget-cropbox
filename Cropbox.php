<?php
namespace bupy7\cropbox;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class file CropboxWidget.
 * Crop image via jQuery before upload image.
 *
 * GitHub repository JS library: https://github.com/hongkhanh/cropbox
 * GitHub repository this widget: https://github.com/bupy7/yii2-cropbox
 * 
 * @author Vasilij "BuPy7" Belosludcev http://mihaly4.ru
 * @version 1.0
 */
class Cropbox extends InputWidget
{
    
    /**
     * @var string Attribute name where will be crop information in JSON format.
     * @property int $x Start crop by X.
     * @property int $y Start crop by Y.
     * @property int $dw Width image after resize.
     * @property int $dh Height image after resize.
     * @property float $ratio Ratio.
     */
    public $attributeCropInfo;
    
    /**
     * @var array Cropbox options.
     * 
     * @property string $thumbBox Class of thumbBox.
     * @property int $thumbWidth Width of thumbBox.
     * @property int $thumbHeiht Height of thumbBox.
     * @property int $thumbMarginTop Property margin-top of thumbBox.
     * @property int $thumbMarginLeft Property margin-left of thumbBox.
     * 
     * and etc. See cropbox.js to assets this widget.
     */
    public $optionsCropbox = [];
    
    /**
     * Link to image for display before upload.
     */
    public $originalUrl;
    public $previewUrl;
	
    /**
     * @var string Path to view of cropbox field.
     * Example: '@app/path/to/view'
     */
    public $pathToView = 'field';
    
    public function init()
    {
        parent::init();
        
        CropboxAsset::register($this->view);
        $this->registerTranslations();
        
        $this->optionsCropbox = array_merge(array(
            'thumbBox' => '.thumbBox',
            'thumbWidth' => 200,
            'thumbHeight' => 200,
        ), $this->optionsCropbox);
        $this->options = array_merge(array(
            'class' => 'file',
        ), $this->options);
        
        $inputCrop = Html::getInputName($this->model, $this->attributeCropInfo);
        $optionsCropbox = Json::encode($this->optionsCropbox);
        
        $js = <<<JS
(function($){
    var options = {$optionsCropbox};

    $('#{$this->id} ' + options.thumbBox).css({
        width: options.thumbWidth,
        height: options.thumbHeight,
        marginTop: options.thumbMarginTop || options.thumbHeight / 2 * -1 ,
        marginLeft: options.thumbMarginLeft || options.thumbWidth / 2 * -1,
    });
    $('#{$this->id} .imageBox').css({
        width: options.thumbWidth + 100,
        height: options.thumbHeight + 100,
    });

    $('#{$this->id} .file').on('change', function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            crop{$this->id} = $('#{$this->id} .imageBox').cropbox(options);
        }
        reader.readAsDataURL(this.files[0]);
        this.files = [];
    });
    $('#{$this->id} .btnCrop').on('click', function(){
        if (typeof crop{$this->id} === 'undefined')
        {
            return false;
        }
        var img = crop{$this->id}.getDataURL(),
            info = crop{$this->id}.getInfo();

        $('#{$this->id} .cropped').html('<img class="img-thumbnail" src="' + img + '">');                                                
        $('input[name="{$inputCrop}"]').val(JSON.stringify({
            x: info.dx,
            y: info.dy,
            dw: info.dw,
            dh: info.dh,
            ratio: info.ratio
        }));
    });
    $('#{$this->id} .btnZoomIn').on('click', function(){
        if (typeof crop{$this->id} !== 'undefined')
        {
            crop{$this->id}.zoomIn();
        }
    });
    $('#{$this->id} .btnZoomOut').on('click', function(){
        if (typeof crop{$this->id} !== 'undefined')
        {
            crop{$this->id}.zoomOut();
        }
    });
})(jQuery);               
JS;
        $this->view->registerJs($js, \yii\web\View::POS_END);
    }
    
    public function run()
    {
        return $this->render($this->pathToView, [
            'idWidget' => $this->id,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'previewUrl' => $this->previewUrl,
            'originalUrl' => $this->originalUrl,
            'options' => $this->options,
            'attributeCropInfo' => $this->attributeCropInfo,
        ]);
    }
    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['bupy7/cropbox/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@bupy7/cropbox/messages',
            'fileMap' => [
                'bupy7/cropbox/core' => 'core.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('bupy7/cropbox/' . $category, $message, $params, $language);
    }

}
