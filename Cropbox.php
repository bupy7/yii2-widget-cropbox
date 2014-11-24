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
     */
    public $attributeCropInfo;
    
    /**
     * @var array Cropbox options.
     */
    public $optionsCropbox = [];
    
    /**
     * Link to image for display before upload.
     */
    public $originalUrl;
    public $thumbUrl;
    
    public function init()
    {
        CropboxAsset::register($this->view);
        
        $this->optionsCropboxCropbox = array_merge(array(
            'thumbBox' => '.thumbBox',
            'thumbWidth' => 200,
            'thumbHeight' => 200,
            'thumbMarginTop' => -100,
            'thumbMarginLeft' => -100,
        ), $this->optionsCropbox);
        $this->htmlOptions = array_merge(array(
            'class' => 'file',
        ), $this->htmlOptions);
        
        $inputCrop = Html::getInputName($this->model, $this->attributeCropInfo);
        $optionsCropbox = Json::encode($this->optionsCropbox);
        
        $js = <<<JS
(function($){
    var options = {$optionsCropbox};

    $('#{$this->id} ' + options.thumbBox).css({
        width: options.thumbWidth,
        height: options.thumbHeight,
        marginTop: options.thumbMarginTop,
        marginLeft: options.thumbMarginLeft,
    });

    $('#{$this->id} .file').on('change', function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            cropper = $('#{$this->id} .imageBox').cropbox(options);
        }
        reader.readAsDataURL(this.files[0]);
        this.files = [];
    });
    $('#{$this->id} .btnCrop').on('click', function(){
        var img = cropper.getDataURL(),
            info = cropper.getInfo();

        $('#{$this->id} .cropped').html('<img class="img-polaroid" src="' + img + '">');                                                
        $('input[name="{$inputCrop}"]').val(JSON.stringify({
            x: info.dx,
            y: info.dy,
            dw: info.dw,
            dh: info.dh
        }));
    });
    $('#{$this->id} .btnZoomIn').on('click', function(){
        cropper.zoomIn();
    });
    $('#{$this->id} .btnZoomOut').on('click', function(){
        cropper.zoomOut();
    });
})(jQuery);               
JS;
        $this->view->registerJs($js, \yii\web\View::POS_END);
    }
    
    public function run()
    {
        return $this->render('field');
    }

}
