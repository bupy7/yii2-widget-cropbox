<?php
/**
 * Class file CropboxWidget.
 * Crop image via jQuery before upload image.
 *
 * GitHub repository JS library: https://github.com/hongkhanh/cropbox
 * GitHub repository this widget:
 * 
 * @author Vasilij "BuPy7" Belosludcev http://mihaly4.ru
 * @version 1.0
 */
class CropboxWidget extends CInputWidget
{
   
    /**
     * @var TbActiveForm when created via TbActiveForm.
     * This attribute is set to the form that renders the widget
     * @see TbActionForm->inputRow
     */
    public $form;
    
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
    public $options = array();
    
    /**
     * @var array Row options for field file.
     */
    public $rowOptions = array();
    
    /**
     * Link to image for display before upload.
     */
    public $originalUrl;
    public $thumbUrl;
    
    public function init()
    {
        $this->options = array_merge(array(
            'thumbBox' => '.thumbBox',
            'thumbWidth' => 200,
            'thumbHeight' => 200,
            'thumbMarginTop' => -100,
            'thumbMarginLeft' => -100,
        ), $this->options);
        $this->htmlOptions = array_merge(array(
            'class' => 'file',
        ), $this->htmlOptions);
        
        $assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.cropbox.assets'));
        Yii::app()->clientScript
            ->registerScriptFile($assetsUrl . '/cropbox.js')
            ->registerCssFile($assetsUrl . '/style.css')
            ->registerScript(
                $this->id . 'cropbox.init', 
                "(function($){
                    var options = " . CJavaScript::encode($this->options) . ";

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
                        
                        $('#{$this->id} .cropped').html('<img class=\"img-polaroid\" src=\"' + img + '\">');                                                
                        $('input[name=\"" . CHtml::resolveName($this->model, $attribute = $this->attributeCropInfo) . "\"]').val(JSON.stringify({
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
            ", CClientScript::POS_END);
    }
    
    public function run()
    {
        $this->render('field');
    }

}
